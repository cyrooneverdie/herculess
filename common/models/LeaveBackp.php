<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

class Leave extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'leave';
    }

    public $fileUpload;

    public function rules()
    {
        return [
            [['contactid', 'leavetype', 'note'], 'string'],
            [['leaveid', 'attachment', 'leaveno'], 'string'],
            [['leaveno'], 'required', 'message' => 'Leave number cannot be empty'],
            [['status'], 'string'],
            [['temperature'], 'string', 'max' => 10],
            [['evaluation'], 'string', 'max' => 100],
            [['fileUpload'], 'file', 'extensions' => 'pdf, doc, docx, jpg, jpeg, png', 'skipOnEmpty' => true],
            [['leavedate', 'leaveduedate', 'updatedat', 'createdat', 'employee_code', 'temperature', 'evaluation'], 'safe'],
            [['leaveid'], 'unique', 'message' => 'Leave ID sudah terdaftar'],

            [['check_in', 'check_out'], 'required', 'when' => function ($model) {

                if (empty($model->leavetype)) return false;

                $enumType = Yii::$app->db->createCommand(
                    "SELECT enumtype FROM enum WHERE enumid = :enumid"
                )->bindValue(':enumid', $model->leavetype)->queryScalar();

                return $enumType === 'absenttype';
            }, 'whenClient' => "function(attribute, value) {
            return false; // Skip client-side validation
        }"],

            [['late_duration', 'overtime_duration'], 'string'],
            [['late_duration', 'overtime_duration'], 'default', 'value' => null],

        ];
    }

    protected function findModel($id)
    {
        if (($model = Leave::findOne(['leaveid' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Data tidak ditemukan.');
    }

    public function attributeLabels()
    {
        return [
            'leaveid'      => 'Leave ID',
            'leaveno'      => 'Leave Number',
            'contactid'    => 'Contact',
            'note'         => 'Note',
            'leavetype'    => 'Leave Type',
            'status'       => 'Status',
            'leavedate'    => 'Leave Date',
            'leaveduedate' => 'Leave Due Date',
            'updatedby'    => 'Updated By',
            'updatedat'    => 'Updated At',
            'updatedip'    => 'Updated IP',
            'attachment'   => 'Attachment',
            'fileUpload'   => 'Upload File',
            'createdby'    => 'Created By',
            'createdat'    => 'Created At',
            'creatdip'     => 'Created IP',
            'check_in'     => 'Jam Masuk / Check In',
            'check_out'    => 'Jam Pulang / Check Out',
            'employee_code' => 'Employee Code',
            'temperature' => 'Temperature (°C)',
            'evaluation' => 'Evaluation Status',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $user = Yii::$app->user->identity;

            if ($this->isNewRecord) {
                $this->createdby = $user->id;
                $this->createdat = new \yii\db\Expression('NOW()');
                $this->creatdip  = $_SERVER['REMOTE_ADDR'];
                $this->status    = $this->status ?? 1;
            } else {
                $this->updatedby = $user->id;
                $this->updatedat = new \yii\db\Expression('NOW()');
                $this->updatedip = $_SERVER['REMOTE_ADDR'];
            }
            return true;
        }
        return false;
    }

    public function getEmployee()
    {
        return $this->hasOne(Employee::class, ['id' => 'contact_id']);
    }

    /**
     * Hitung keterlambatan berdasarkan check_in
     * Return format: "30 menit" atau null jika tidak telat
     */
    public function calculateLateDuration()
    {
        if (empty($this->check_in) || empty($this->leavedate)) {
            return null;
        }

        try {
            // Parse check_in time
            $checkInTime = new \DateTime($this->check_in);
            $checkInHour = (int)$checkInTime->format('H');
            $checkInMinute = (int)$checkInTime->format('i');

            // Jam operasional mulai: 08:00
            $operationalStartHour = 8;
            $operationalStartMinute = 0;

            // Hitung selisih dalam menit
            $actualMinutes = ($checkInHour * 60) + $checkInMinute;
            $operationalMinutes = ($operationalStartHour * 60) + $operationalStartMinute;
            $lateMinutes = $actualMinutes - $operationalMinutes;

            // Toleransi 5 menit - jika telat <= 5 menit, dianggap tidak telat
            if ($lateMinutes <= 5) {
                return null;
            }

            // Format output
            if ($lateMinutes >= 60) {
                $hours = floor($lateMinutes / 60);
                $minutes = $lateMinutes % 60;
                if ($minutes > 0) {
                    return "{$hours} jam {$minutes} menit";
                }
                return "{$hours} jam";
            }

            return "{$lateMinutes} menit";
        } catch (\Exception $e) {
            \Yii::error('Error calculating late duration: ' . $e->getMessage(), 'leave');
            return null;
        }
    }

    /**
     * Hitung lembur berdasarkan check_out
     * Return format: "2 jam 30 menit" atau null jika tidak lembur
     */
    public function calculateOvertimeDuration()
    {
        if (empty($this->check_out) || empty($this->leavedate)) {
            return null;
        }

        try {
            // Parse tanggal untuk cek hari (1=Senin, 7=Minggu)
            $date = new \DateTime($this->leavedate);
            $dayOfWeek = (int)$date->format('N');

            // Parse check_out time
            $checkOutTime = new \DateTime($this->check_out);
            $checkOutHour = (int)$checkOutTime->format('H');
            $checkOutMinute = (int)$checkOutTime->format('i');

            // Tentukan jam operasional berdasarkan hari
            // Senin-Jumat (1-5): jam pulang 17:00
            // Sabtu-Minggu (6-7): jam pulang 12:00
            $operationalEndHour = ($dayOfWeek >= 1 && $dayOfWeek <= 5) ? 17 : 12;
            $operationalEndMinute = 0;

            // Hitung selisih dalam menit
            $actualMinutes = ($checkOutHour * 60) + $checkOutMinute;
            $operationalMinutes = ($operationalEndHour * 60) + $operationalEndMinute;
            $overtimeMinutes = $actualMinutes - $operationalMinutes;

            // Jika tidak ada lembur (bahkan 1 menit lebih cepat = tidak lembur)
            if ($overtimeMinutes <= 0) {
                return null;
            }

            // Format output
            if ($overtimeMinutes >= 60) {
                $hours = floor($overtimeMinutes / 60);
                $minutes = $overtimeMinutes % 60;
                if ($minutes > 0) {
                    return "{$hours} jam {$minutes} menit";
                }
                return "{$hours} jam";
            }

            return "{$overtimeMinutes} menit";
        } catch (\Exception $e) {
            \Yii::error('Error calculating overtime duration: ' . $e->getMessage(), 'leave');
            return null;
        }
    }

    /**
     * Auto-calculate dan save late_duration & overtime_duration
     * Panggil method ini sebelum save()
     */
    public function autoCalculateDurations()
    {
        $this->late_duration = $this->calculateLateDuration();
        $this->overtime_duration = $this->calculateOvertimeDuration();

        \Yii::info('📊 Auto-calculated Late: ' . ($this->late_duration ?? 'NULL'), 'leave');
        \Yii::info('📊 Auto-calculated Overtime: ' . ($this->overtime_duration ?? 'NULL'), 'leave');
    }


    /**
     * ✅ Generate nomor dengan prefix dinamis
     * Leave -> LV-0001
     * Absent -> AT-0001
     */

    // public static function nextNo($leavetype = '')
    // {
    //     $companyid = Yii::$app->session->get('companyid');
    //     $prefix = 'LV'; // Default prefix

    //     Yii::info("🔢 nextNo() called with leavetype: '$leavetype'", 'leave');

    //     // Deteksi prefix berdasarkan leavetype
    //     if ($leavetype === 'absent') {
    //         $prefix = 'AT';
    //         Yii::info("✓ Prefix set to AT (absent)", 'leave');
    //     } elseif ($leavetype === 'leave') {
    //         $prefix = 'LV';
    //         Yii::info("✓ Prefix set to LV (leave)", 'leave');
    //     } elseif (!empty($leavetype) && strlen($leavetype) > 10) {
    //         try {
    //             $enumType = Yii::$app->db->createCommand(
    //                 "SELECT enumtype FROM enum WHERE enumid = :enumid"
    //             )->bindValue(':enumid', $leavetype)->queryScalar();

    //             Yii::info("🔍 Detected enumid, enumtype: '$enumType'", 'leave');

    //             if ($enumType === 'absenttype') {
    //                 $prefix = 'AT';
    //             } elseif ($enumType === 'leavetype') {
    //                 $prefix = 'LV';
    //             }
    //         } catch (\Exception $e) {
    //             Yii::error("❌ Error detecting enumtype: " . $e->getMessage(), 'leave');
    //         }
    //     }

    //     Yii::info("🔢 Final prefix: $prefix", 'leave');

    //     try {
    //         // ✅ PERBAIKAN: Ganti regex pattern untuk support leading zeros
    //         // Pattern lama: '^{$prefix}-[0-9]+$' ❌
    //         // Pattern baru: '^{$prefix}-[0-9]' ✅ (tanpa $ di akhir, lebih permisif)
    //         $sql = "
    //         SELECT COALESCE(
    //             MAX(
    //                 CAST(
    //                     REGEXP_REPLACE(leaveno, '[^0-9]', '', 'g') AS INTEGER
    //                 )
    //             ), 
    //             0
    //         ) + 1 AS next_number
    //         FROM \"leave\" 
    //         WHERE leaveno LIKE :prefix
    //         AND status != 10
    //     ";

    //         if ($companyid) {
    //             $sql .= " AND companyid = :companyid";
    //         }

    //         Yii::info("📊 NextNo SQL: " . $sql, 'leave');

    //         $command = Yii::$app->db->createCommand($sql);
    //         $command->bindValue(':prefix', $prefix . '-%');

    //         if ($companyid) {
    //             $command->bindValue(':companyid', $companyid);
    //         }

    //         $result = $command->queryOne();
    //         $nextNumber = $result['next_number'] ?? 1;

    //         // Format: AT-0001 atau LV-0001
    //         $fullKode = $prefix . '-' . str_pad($nextNumber, 4, "0", STR_PAD_LEFT);

    //         Yii::info("✅ Generated Leave No: $fullKode (from number: $nextNumber)", 'leave');

    //         return $fullKode;
    //     } catch (\Exception $e) {
    //         Yii::error("❌ Error generating leave number: " . $e->getMessage(), 'leave');
    //         Yii::error("Stack trace: " . $e->getTraceAsString(), 'leave');

    //         // Fallback: cari manual dengan sorting
    //         $lastRecord = self::find()
    //             ->where(['LIKE', 'leaveno', $prefix . '-'])
    //             ->andWhere(['!=', 'status', 10])
    //             ->orderBy([
    //                 new \yii\db\Expression("CAST(REGEXP_REPLACE(leaveno, '[^0-9]', '', 'g') AS INTEGER) DESC")
    //             ])
    //             ->one();

    //         if ($lastRecord && preg_match('/-(\d+)$/', $lastRecord->leaveno, $matches)) {
    //             $nextNumber = (int)$matches[1] + 1;
    //         } else {
    //             $nextNumber = 1;
    //         }

    //         return $prefix . '-' . str_pad($nextNumber, 4, "0", STR_PAD_LEFT);
    //     }
    // }

    /**
     * Generate BIOFINGER format export data
     */

    public static function exportToBiofingerFormat($startDate, $endDate, $leavetype = 'absent')
    {
        $query = self::find()
            ->alias('l')
            ->select([
                'l.*',
                'c.contact_name',
                'COALESCE(l.employee_code, c.contact_no, \'N/A\') AS employee_code',
                'c.contact_no',
                'c.contact_phone1',
                'p.enumtext_id AS position_name',
                'd.enumtext_id AS division_name',
                'lt.enumtext_id AS leavetype_name',
                'lt.enumtype'
            ])
            ->leftJoin('contacts c', 'c.contact_id = l.contactid')
            ->leftJoin('enum p', 'p.enumid = c.positionid')
            ->leftJoin('enum d', 'd.enumid = c.divisionid')
            ->leftJoin('enum lt', 'lt.enumid = l.leavetype')
            ->where(['l.status' => '1'])
            ->andWhere(['>=', 'l.leavedate', $startDate])
            ->andWhere(['<=', 'l.leaveduedate', $endDate])
            ->orderBy(['c.contact_name' => SORT_ASC, 'l.leavedate' => SORT_ASC]);

        // Filter by leavetype
        if ($leavetype === 'absent') {
            $query->andWhere(['lt.enumtype' => 'absenttype']);
        } elseif ($leavetype === 'leave') {
            $query->andWhere(['lt.enumtype' => 'leavetype']);
        } elseif ($leavetype === 'parttime') {
            $query->andWhere(['lt.enumtype' => 'parttimetype']);
        }

        return $query->all();
    }
    // public static function exportToBiofingerFormat($startDate, $endDate, $leavetype = 'absent')
    // {
    //     $query = self::find()
    //         ->alias('l')
    //         ->select([
    //             'l.*',
    //             'c.contact_name',
    //             'c.contact_no AS employee_code',
    //             'c.contact_phone1',
    //             'p.enumtext_id AS position_name',
    //             'd.enumtext_id AS division_name',
    //             'lt.enumtext_id AS leavetype_name',
    //             'lt.enumtype'
    //         ])
    //         ->leftJoin('contacts c', 'c.contact_id = l.contactid')
    //         ->leftJoin('enum p', 'p.enumid = c.positionid')
    //         ->leftJoin('enum d', 'd.enumid = c.divisionid')
    //         ->leftJoin('enum lt', 'lt.enumid = l.leavetype')
    //         ->where(['l.status' => '1'])
    //         ->andWhere(['>=', 'l.leavedate', $startDate])
    //         ->andWhere(['<=', 'l.leaveduedate', $endDate])
    //         ->orderBy(['c.contact_name' => SORT_ASC, 'l.leavedate' => SORT_ASC]);

    //     // Filter by leavetype
    //     if ($leavetype === 'absent') {
    //         $query->andWhere(['lt.enumtype' => 'absenttype']);
    //     } elseif ($leavetype === 'leave') {
    //         $query->andWhere(['lt.enumtype' => 'leavetype']);
    //     }

    //     return $query->all();
    // }

    public static function nextNo($leavetype = '')
    {
        // $companyid = Yii::$app->session->get('companyid');
        $prefix = 'LV';

        Yii::info("🔢 nextNo() called with leavetype: '$leavetype'", 'leave');

        if ($leavetype === 'absent') {
            $prefix = 'AT';
        } elseif ($leavetype === 'leave') {
            $prefix = 'LV';
        } elseif ($leavetype === 'parttime') { 
            $prefix = 'PT';
        
        } elseif (!empty($leavetype) && strlen($leavetype) > 10) {
            try {
                $enumType = Yii::$app->db->createCommand(
                    "SELECT enumtype FROM enum WHERE enumid = :enumid"
                )->bindValue(':enumid', $leavetype)->queryScalar();

                if ($enumType === 'absenttype') {
                    $prefix = 'AT';
                } elseif ($enumType === 'leavetype') {
                    $prefix = 'LV';
                } elseif ($enumType === 'parttimetype') { 
                    $prefix = 'PT';
                }

            } catch (\Exception $e) {
                Yii::error("❌ Error detecting enumtype: " . $e->getMessage(), 'leave');
            }
        }

        try {
            
            $sql = "
            SELECT COALESCE(
                MAX(
                    CAST(
                        REGEXP_REPLACE(leaveno, '[^0-9]', '', 'g') AS INTEGER
                    )
                ), 
                0
            ) + 1 AS next_number
            FROM \"leave\" 
            WHERE leaveno LIKE :prefix
            AND leaveno IS NOT NULL
            AND leaveno != ''
            AND status != '10'
        ";

            // if ($companyid) {
            //     $sql .= " AND companyid = :companyid";
            // }

            $command = Yii::$app->db->createCommand($sql);
            $command->bindValue(':prefix', $prefix . '-%');

            // if ($companyid) {
            //     $command->bindValue(':companyid', $companyid);
            // }

            $result = $command->queryOne();
            $nextNumber = $result['next_number'] ?? 1;

            $fullKode = $prefix . '-' . str_pad($nextNumber, 4, "0", STR_PAD_LEFT);

            Yii::info("✅ Generated Leave No: $fullKode", 'leave');

            return $fullKode;
        } catch (\Exception $e) {
            Yii::error("❌ Error generating leave number: " . $e->getMessage(), 'leave');

            // ✅ FIX: Fallback dengan filter yang lebih ketat
            try {
                $lastRecord = self::find()
                    ->where(['LIKE', 'leaveno', $prefix . '-'])
                    ->andWhere(['!=', 'status', '10'])
                    ->andWhere(['IS NOT', 'leaveno', null])  // ✅ Tambahkan ini
                    ->andWhere(['!=', 'leaveno', ''])        // ✅ Tambahkan ini
                    ->orderBy([
                        new \yii\db\Expression("CAST(REGEXP_REPLACE(leaveno, '[^0-9]', '', 'g') AS INTEGER) DESC")
                    ])
                    ->one();

                if ($lastRecord && preg_match('/-(\d+)$/', $lastRecord->leaveno, $matches)) {
                    $nextNumber = (int)$matches[1] + 1;
                } else {
                    $nextNumber = 1;
                }

                Yii::info("✅ Fallback generated Leave No: $prefix-" . str_pad($nextNumber, 4, "0", STR_PAD_LEFT), 'leave');

                return $prefix . '-' . str_pad($nextNumber, 4, "0", STR_PAD_LEFT);
            } catch (\Exception $fallbackError) {
                // ✅ ULTIMATE FALLBACK: Return nomor awal
                Yii::error("❌ Fallback also failed: " . $fallbackError->getMessage(), 'leave');
                return $prefix . '-0001';
            }
        }
    }



    public function search($params)
    {
        $query = Leave::find()
            ->where(['!=', 'status', 10]);

        if (!empty($params['search'])) {
            $query->andFilterWhere([
                'or',
                ['ilike', 'leaveno', $params['search']],
                ['ilike', 'note', $params['search']],
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => isset($params['per-page']) && $params['per-page'] != "-"
                    ? $params['per-page']
                    : 10
            ],
            'sort' => ['defaultOrder' => ['createdat' => SORT_DESC]],
        ]);
    }

    // public function beforeValidate()
    // {
    //     // Generate UUID untuk leaveid (primary key)
    //     if ($this->isNewRecord && empty($this->leaveid)) {
    //         $this->leaveid = Yii::$app->db
    //             ->createCommand("SELECT uuid_generate_v4()")
    //             ->queryScalar();
    //     }

    //     // Generate nomor leave otomatis - PASTIKAN di sini
    //     if ($this->isNewRecord && empty($this->leaveno)) {
    //         $this->leaveno = self::nextNo($this->leavetype);
    //         Yii::info("Auto-generated leaveno in beforeValidate: " . $this->leaveno, 'leave');
    //     }

    //     return parent::beforeValidate();
    // }


    public function beforeValidate()
    {
        // Generate UUID untuk leaveid (primary key)
        if ($this->isNewRecord && empty($this->leaveid)) {
            $this->leaveid = Yii::$app->db
                ->createCommand("SELECT uuid_generate_v4()")
                ->queryScalar();
        }

        // ✅ Generate nomor leave otomatis dengan validasi ekstra
        if ($this->isNewRecord && (empty($this->leaveno) || trim($this->leaveno) === '')) {
            $this->leaveno = self::nextNo($this->leavetype);
            Yii::info("Auto-generated leaveno in beforeValidate: " . $this->leaveno, 'leave');

            // ✅ Validasi hasil generate
            if (empty($this->leaveno) || trim($this->leaveno) === '') {
                Yii::error("Failed to generate leaveno, setting default", 'leave');
                $this->leaveno = 'LV-0001'; // Default fallback
            }
        }

        return parent::beforeValidate();
    }
}

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
            [['contactid', 'leavetype', 'note', 'contact_id'], 'string'],
            [['leaveid', 'attachment', 'leaveno'], 'string'],
            [['leaveno'], 'required', 'message' => 'Leave number cannot be empty'],
            [['status', 'payment_status', 'leave_status'], 'string'],
            [['temperature'], 'string', 'max' => 10],
            [['evaluation'], 'string', 'max' => 100],
            [['fileUpload'], 'file', 'extensions' => 'pdf, doc, docx, jpg, jpeg, png', 'skipOnEmpty' => true],
            [['leavedate', 'leaveduedate', 'updatedat', 'createdat', 'employee_code', 'temperature', 'evaluation', 'check_out'], 'safe'],
            [['leaveid'], 'unique', 'message' => 'Leave ID sudah terdaftar'],

            [['grade', 'current_work_status', 'working_at', 'current_position', 'company_phone'], 'string'],
            [['grade'], 'in', 'range' => ['A', 'B', 'C', 'D']],

            [['payment', 'amount'], 'string'],
            [['total'], 'number'],
            [['payment_date'], 'safe'],

            [['payment'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\Enum::class, 'targetAttribute' => ['payment' => 'enumid']],
            [
                ['identitycardfile', 'studentcardfile', 'pict_employee', 'pict_employee2'],
                'file',
                'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
                'maxSize' => 5 * 1024 * 1024, // 5MB
                'skipOnEmpty' => true
            ],

            [
                ['check_in'],
                'required',
                'when' => function ($model) {

                    if (empty($model->leavetype))
                        return false;

                    $enumType = Yii::$app->db->createCommand(
                        "SELECT enumtype FROM enum WHERE enumid = :enumid"
                    )->bindValue(':enumid', $model->leavetype)->queryScalar();

                    return $enumType === 'absenttype';
                },
                'whenClient' => "function(attribute, value) {
            return false; // Skip client-side validation
        }"
            ],

            [['late_duration', 'overtime_duration'], 'string'],
            [['late_duration', 'overtime_duration'], 'default', 'value' => null],

            // PARTTIME PERSONAL DATA
            [['parttime_no', 'parttime_name'], 'string', 'max' => 255],
            [['member_level', 'jenis_parttime'], 'string'],
            [['is_active'], 'boolean'],
            [['tanggal_mulai', 'contact_bod'], 'date', 'format' => 'php:Y-m-d'],
            [['nik'], 'string', 'max' => 16],
            [['nik'], 'match', 'pattern' => '/^[0-9]{16}$/', 'message' => 'NIK harus 16 digit angka'],
            [['birth_place', 'birth_date'], 'string'],
            [['birth_date'], 'date', 'format' => 'php:Y-m-d'],

            // Personal Info
            [['contact_phone1', 'contact_email1', 'contact_birth'], 'string'],
            [['contact_gender', 'contact_married', 'contact_religion'], 'string'],

            // Address
            [['alamat_tinggal'], 'string'],
            [['countryid', 'stateid', 'cityid', 'districtid', 'zip'], 'string'],

            // Education
            [['pendidikan_sekolah', 'alamat_sekolah', 'jurusan', 'pengalaman_kerja'], 'string'],

            // Family
            [['npwpno'], 'string'],
            [['mother_name', 'mother_bop', 'mother_phone', 'mother_work', 'mother_address'], 'string'],
            [['mother_bod'], 'date', 'format' => 'php:Y-m-d'],
            [['father_name', 'father_bop', 'father_phone', 'father_work', 'father_address'], 'string'],
            [['father_bod'], 'date', 'format' => 'php:Y-m-d'],
            [['guardian_name', 'guardian_bop', 'guardian_phone', 'guardian_work', 'guardian_address'], 'string'],
            [['guardian_bod'], 'date', 'format' => 'php:Y-m-d'],
            [['spouse_name', 'spouse_bop', 'spouse_phone', 'spouse_work', 'spouse_address'], 'string'],
            [['spouse_bod'], 'date', 'format' => 'php:Y-m-d'],

            // Payment Info
            [['bankname', 'bankaccount_no', 'account_owner_name', 'hubungan_pemilik_rekening'], 'string'],
            [['keterangan_khusus'], 'string'],

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
            'leaveid' => 'Leave ID',
            'leaveno' => 'Leave Number',
            'contactid' => 'Contact',
            'note' => 'Note',
            'leavetype' => 'Leave Type',
            'status' => 'Status',
            'leavedate' => 'Leave Date',
            'leaveduedate' => 'Leave Due Date',
            'updatedby' => 'Updated By',
            'updatedat' => 'Updated At',
            'updatedip' => 'Updated IP',
            'attachment' => 'Attachment',
            'fileUpload' => 'Upload File',
            'createdby' => 'Created By',
            'createdat' => 'Created At',
            'creatdip' => 'Created IP',
            'check_in' => 'Jam Masuk / Check In',
            'check_out' => 'Jam Pulang / Check Out',
            'employee_code' => 'Employee Code',
            'temperature' => 'Temperature (°C)',
            'evaluation' => 'Evaluation Status',
            'grade' => 'Grade',
            'current_work_status' => 'Status Kerja Saat Ini',
            'working_at' => 'Bekerja Di',
            'current_position' => 'Jabatan',
            'company_phone' => 'Telp Perusahaan',
            'payment' => 'Metode Pembayaran',
            'total' => 'Total Pembayaran',
            'amount' => 'Jumlah (Rp)',
            'payment_date' => 'Tanggal Pembayaran',

            // PARTTIME LABELS
            'parttime_no' => 'Kode Parttime',
            'parttime_name' => 'Nama Parttime',
            'member_level' => 'Member Level',
            'is_active' => 'Status Aktif',
            'jenis_parttime' => 'Jenis Parttime',
            'payment_status' => 'Status Pembayaran',
            'tanggal_mulai' => 'Tanggal Mulai',
            'contact_phone1' => 'No. Telepon',
            'contact_email1' => 'Email',
            'nik' => 'NIK (Nomor Induk Kependudukan)',
            'birth_place' => 'Tempat Lahir',
            'birth_date' => 'Tanggal Lahir',
            'identitycardfile' => 'Foto KTP',
            'studentcardfile' => 'Foto Kartu Pelajar',
            'contact_birth' => 'Tempat Lahir',
            'contact_bod' => 'Tanggal Lahir',
            'contact_gender' => 'Jenis Kelamin',
            'contact_married' => 'Status Pernikahan',
            'contact_religion' => 'Agama',
            'alamat_tinggal' => 'Alamat Tinggal',
            'pendidikan_sekolah' => 'Sekolah/Universitas',
            'alamat_sekolah' => 'Alamat Sekolah/Kantor',
            'jurusan' => 'Jurusan',
            'pengalaman_kerja' => 'Pengalaman Kerja',
            'mother_name' => 'Nama Ibu',
            'father_name' => 'Nama Ayah',
            'guardian_name' => 'Nama Wali',
            'spouse_name' => 'Nama Suami/Istri',
            'bankname' => 'Nama Bank',
            'bankaccount_no' => 'Nomor Rekening',
            'account_owner_name' => 'Nama Pemilik Rekening',
            'hubungan_pemilik_rekening' => 'Hubungan dengan Pemilik',
            'keterangan_khusus' => 'Keterangan Khusus',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $user = Yii::$app->user->identity;

            $this->autoCalculateDurations();

            if ($this->isNewRecord) {
                $this->createdby = $user->id;
                $this->createdat = new \yii\db\Expression('NOW()');
                $this->creatdip = $_SERVER['REMOTE_ADDR'];
                $this->status = $this->status ?? 1;
                if (empty($this->leave_status)) {
                    $this->leave_status = 0;
                }
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

    public function autoCalculateDurations()
    {
        if (empty($this->check_in) || empty($this->leavedate)) {
            return;
        }

        $tz = new \DateTimeZone('Asia/Jakarta');
        $checkInTime = new \DateTime($this->check_in, $tz);

        $workStart = null;
        if (!empty($this->traneventid)) {
            $startdateSql =
                "SELECT startdate FROM tranevents 
            WHERE traneventid = '$this->traneventid' AND status <> '10'";
            $startdateResult = Yii::$app->db->createCommand($startdateSql)->queryScalar();

            if ($startdateResult && trim($startdateResult) !== '' && strpos($startdateResult, '00:00:00') === false) {
                $workStart = new \DateTime($startdateResult, $tz);
            }

        }

        if (!$workStart) {
            $workStart = new \DateTime($this->leavedate . ' 08:00:00', $tz);
        }

        $diffMinutes = floor(($checkInTime->getTimestamp() - $workStart->getTimestamp()) / 60);

        if ($diffMinutes >= 1) {
            if ($diffMinutes >= 60) {
                $hours = floor($diffMinutes / 60);
                $mins = (int) ($diffMinutes % 60);
                $this->late_duration = $mins > 0 ? "{$hours} jam {$mins} menit" : "{$hours} jam";
            } else {
                $this->late_duration = floor($diffMinutes) . " menit";
            }
        } else {
            $this->late_duration = null;
        }

        if (!empty($this->check_out)) {
            $checkOutTime = new \DateTime($this->check_out, $tz);

            $workEnd = null;
            if (!empty($this->traneventid)) {
                $enddateSql =
                    "SELECT enddate FROM tranevents 
                WHERE traneventid = '$this->traneventid' AND status <> '10'";
                $enddateResult = Yii::$app->db->createCommand($enddateSql)->queryScalar();

                if ($enddateResult && trim($enddateResult) !== '' && strpos($enddateResult, '00:00:00') === false) {
                    $workEnd = new \DateTime($enddateResult, $tz);
                }
            }

            if (!$workEnd) {
                $workEnd = new \DateTime($this->leavedate . ' 17:00:00', $tz);
            }

            $diffMinutesOt = floor(($checkOutTime->getTimestamp() - $workEnd->getTimestamp()) / 60);

            if ($diffMinutesOt >= 1) {
                if ($diffMinutesOt >= 60) {
                    $hours = floor($diffMinutesOt / 60);
                    $mins = (int) ($diffMinutesOt % 60);
                    $this->overtime_duration = $mins > 0 ? "{$hours} jam {$mins} menit" : "{$hours} jam";
                } else {
                    $this->overtime_duration = floor($diffMinutesOt) . " menit";
                }
            } else {
                $this->overtime_duration = null;
            }
        }
    }

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

        if ($leavetype === 'absent') {
            $query->andWhere(['lt.enumtype' => 'absenttype']);
        } elseif ($leavetype === 'leave') {
            $query->andWhere(['lt.enumtype' => 'leavetype']);
        } elseif ($leavetype === 'parttime') {
            $query->andWhere(['lt.enumtype' => 'parttimetype']);
        }

        return $query->all();
    }

    public static function nextNo($leavetype = '')
    {
        $prefix = 'LV';

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

            $command = Yii::$app->db->createCommand($sql);
            $command->bindValue(':prefix', $prefix . '-%');

            $result = $command->queryOne();
            $nextNumber = $result['next_number'] ?? 1;

            $fullKode = $prefix . '-' . str_pad($nextNumber, 4, "0", STR_PAD_LEFT);

            Yii::info("Generated Leave No: $fullKode", 'leave');

            return $fullKode;
        } catch (\Exception $e) {
            Yii::error("❌ Error generating leave number: " . $e->getMessage(), 'leave');

            // FIX: Fallback dengan filter yang lebih ketat
            try {
                $lastRecord = self::find()
                    ->where(['LIKE', 'leaveno', $prefix . '-'])
                    ->andWhere(['!=', 'status', '10'])
                    ->andWhere(['IS NOT', 'leaveno', null])  // Tambahkan ini
                    ->andWhere(['!=', 'leaveno', ''])        // Tambahkan ini
                    ->orderBy([
                        new \yii\db\Expression("CAST(REGEXP_REPLACE(leaveno, '[^0-9]', '', 'g') AS INTEGER) DESC")
                    ])
                    ->one();

                if ($lastRecord && preg_match('/-(\d+)$/', $lastRecord->leaveno, $matches)) {
                    $nextNumber = (int) $matches[1] + 1;
                } else {
                    $nextNumber = 1;
                }

                Yii::info("Fallback generated Leave No: $prefix-" . str_pad($nextNumber, 4, "0", STR_PAD_LEFT), 'leave');

                return $prefix . '-' . str_pad($nextNumber, 4, "0", STR_PAD_LEFT);
            } catch (\Exception $fallbackError) {
                // ULTIMATE FALLBACK: Return nomor awal
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

    public function beforeValidate()
    {
        if ($this->isNewRecord && empty($this->leaveid)) {
            $this->leaveid = Yii::$app->db
                ->createCommand("SELECT uuid_generate_v4()")
                ->queryScalar();
        }

        if ($this->isNewRecord && !empty($this->parttime_name) && empty($this->parttime_no)) {
            $this->parttime_no = $this->generateParttimeNo();
            Yii::info("Auto-generated parttime_no: " . $this->parttime_no, 'leave');
        }

        if ($this->isNewRecord && (empty($this->leaveno) || trim($this->leaveno) === '')) {
            $this->leaveno = self::nextNo($this->leavetype);
            Yii::info("Auto-generated leaveno in beforeValidate: " . $this->leaveno, 'leave');

            if (empty($this->leaveno) || trim($this->leaveno) === '') {
                Yii::error("Failed to generate leaveno, setting default", 'leave');
                $this->leaveno = 'LV-0001'; // Default fallback
            }
        }

        return parent::beforeValidate();
    }

    private function generateParttimeNo()
    {
        $sql = "
        SELECT COALESCE(
            MAX(CAST(parttime_no AS INTEGER)), 
            10000
        ) + 1 AS next_number
        FROM \"leave\" 
        WHERE parttime_no IS NOT NULL
        AND parttime_no ~ '^[0-9]+$'
        AND status != '10'
    ";

        try {
            $result = Yii::$app->db->createCommand($sql)->queryOne();
            $nextNumber = $result['next_number'] ?? 10001;

            Yii::info("Generated Parttime No: $nextNumber", 'leave');

            return (string) $nextNumber;
        } catch (\Exception $e) {
            Yii::error("❌ Error generating parttime number: " . $e->getMessage(), 'leave');
            return '10001';
        }
    }


    public function calculateLateDuration()
    {
        if (empty($this->check_in) || empty($this->leavedate)) {
            return null;
        }

        try {
            $checkInTime = new \DateTime($this->check_in);
            $checkInHour = (int) $checkInTime->format('H');
            $checkInMinute = (int) $checkInTime->format('i');

            $operationalStartHour = 8;
            $operationalStartMinute = 0;

            $actualMinutes = ($checkInHour * 60) + $checkInMinute;
            $operationalMinutes = ($operationalStartHour * 60) + $operationalStartMinute;
            $lateMinutes = $actualMinutes - $operationalMinutes;

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
    public function calculateOvertimeDuration()
    {
        if (empty($this->check_out) || empty($this->leavedate)) {
            return null;
        }

        try {
            $date = new \DateTime($this->leavedate);
            $dayOfWeek = (int) $date->format('N');

            $checkOutTime = new \DateTime($this->check_out);
            $checkOutHour = (int) $checkOutTime->format('H');
            $checkOutMinute = (int) $checkOutTime->format('i');

            $operationalEndHour = ($dayOfWeek >= 1 && $dayOfWeek <= 5) ? 18 : 12;
            $operationalEndMinute = 0;

            $actualMinutes = ($checkOutHour * 60) + $checkOutMinute;
            $operationalMinutes = ($operationalEndHour * 60) + $operationalEndMinute;
            $overtimeMinutes = $actualMinutes - $operationalMinutes;

            if ($overtimeMinutes <= 0) {
                return null;
            }

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
}

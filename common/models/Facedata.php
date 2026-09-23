<?php

namespace common\models;

use Yii;

/**
 * Model untuk tabel "facedata"
 *
 * @property string      $face_id
 * @property int         $user_id
 * @property string|null $registration_date
 */
class Facedata extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'facedata';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [

                'default',
                'value' => null
            ],
            [['face_id', 'user_id'], 'required'],
           
            [['registration_date', 'validation_date'], 'safe'],
            [['face_id'], 'string', 'max' => 50],
            [['face_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'face_id' => 'Face ID',
            'user_id' => 'User ID',
            'foto_blink' => 'Foto Challenge Kedip',
            'foto_smile' => 'Foto Challenge Senyum',
            'foto_left' => 'Foto Challenge Kiri',
            'foto_right' => 'Foto Challenge Kanan',
            'note' => 'Catatan',
            'validation_status' => 'Status Validasi',
            'validate_by' => 'Divalidasi Oleh',
            'registration_date' => 'Tanggal Registrasi',
            'validation_date' => 'Tanggal Validasi',
            'rejection_reason' => 'Alasan Penolakan',
        ];
    }

    /**
     * Helper — ambil semua URL foto challenge yang sudah tersimpan
     * @param  string $baseUrl  e.g. 'https://dev.amgvision.xyz/'
     * @return array
     */
    public function getChallengePhotoUrls(string $baseUrl = ''): array
    {
        $map = [
            'blink' => $this->foto_blink,
            'smile' => $this->foto_smile,
            'left' => $this->foto_left,
            'right' => $this->foto_right,
        ];

        $result = [];
        foreach ($map as $key => $path) {
            $result[$key] = $path ? rtrim($baseUrl, '/') . '/' . $path : null;
        }

        return $result;
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->isNewRecord && empty($this->face_id)) {
                $this->face_id = \Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
            }

            if ($this->isNewRecord) {
                if (empty($this->registration_date)) {
                    $this->registration_date = date('Y-m-d H:i:s');
                }

                if (empty($this->validation_status)) {
                    $this->validation_status = 'Pending';
                }
            }

            return true;
        }
        return false;
    }

}
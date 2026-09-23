<?php

namespace app\models;
namespace common\models;

use Yii;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "faq".
 *
 * @property int $faqid
 * @property string|null $question
 * @property string|null $answer
 * @property string|null $faqtype
 * @property int|null $views
 * @property int|null $helpful
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Faq extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'faq';
    }

    public function rules()
    {
        return [
            [['question', 'answer', 'faqtype'], 'string'],
            [['views', 'helpful'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'faqid' => 'Faq ID',
            'question' => 'Question',
            'answer' => 'Answer',
            'faqtype' => 'Faq Type',
            'views' => 'Views',
            'helpful' => 'Helpful',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

        public static function getTypes()
    {
        return [
            'pengguna' => 'Pengguna',
            'sistem' => 'Sistem',
            'akun' => 'Akun',
            'transaksi' => 'Transaksi',
            'lainnya' => 'Lainnya',
        ];
    }
}

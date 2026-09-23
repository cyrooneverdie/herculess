<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\data\SqlDataProvider;

/**
 * User model
 * @var $userid string
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $oldpassword;
    public $position_name;

    public $position;

    public $passwordnow;
    public $password;
    const STATUS_DELETED = 10;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public static function tableName()
    {
        return '{{%users}}';
    }

    public function rules()
    {
        return [
            [['username', 'email', 'password', 'contact_id', 'position'], 'safe'],
            [['positionid'], 'string'],
            [['positionid'], 'safe'],
            [['oldpassword', 'passwordnow'], 'required', 'on' => 'resetPassword'],
            ['oldpassword', 'validateOldPassword', 'on' => 'resetPassword'],
            ['passwordnow', 'string', 'min' => 4, 'on' => 'resetPassword'],
            [['username', 'name', 'email', 'password'], 'required', 'on' => 'createUser'],
            [['avatar'], 'safe'],
            [['password'], 'string', 'min' => 4],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            [['role_id', 'name', 'address', 'contact_id'], 'safe'],
            ['username', 'unique', 'targetClass' => self::class, 'message' => 'Username sudah digunakan.'],
            ['name', 'unique', 'targetClass' => self::class, 'message' => 'Nama sudah digunakan.'],
            [
                'name',
                'unique',
                'targetClass' => self::class,
                'filter' => function ($query) {
                    if (!$this->isNewRecord) {
                        $query->andWhere(['<>', 'userid', $this->userid]);
                    }
                },
                'message' => 'Nama sudah digunakan.'
            ],
            [
                'username',
                'unique',
                'targetClass' => self::class,
                'filter' => function ($query) {
                    if (!$this->isNewRecord) {
                        $query->andWhere(['<>', 'userid', $this->userid]);
                    }
                },
                'message' => 'Username sudah digunakan.'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'positionid' => Yii::$app->lang->t('contact', 'position'),
            'contact_id' => Yii::$app->lang->t('front_home', 'contact'),

        ];
    }


    /** IdentityInterface **/
    public static function findIdentity($id)
    {
        return static::find()
            ->where(['userid' => $id])
            ->andWhere(['<>', 'status', self::STATUS_DELETED])
            ->one();
    }

    public function resetPasswordToName()
    {
        $this->setPassword($this->name);
        $this->generateAuthKey(); // session lama
        return $this->save(false);
    }

    public function init()
    {
        parent::init();
        if ($this->isNewRecord) {
            $this->status = 1; // default aktif
        }
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function getGrup()
    {
        return $this->role_id;
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function getEnum()
    {
        return $this->hasOne(Enum::class, ['enumid' => 'positionid']);
    }


    /** Password helpers **/
    public function validatePassword($password)
    {
        // cek bcrypt
        if (Yii::$app->security->validatePassword($password, $this->password_hash)) {
            return true;
        }
        // cek MD5 lama
        if (
            strpos($this->password_hash, '$1$') === 0 &&
            md5($password) === substr($this->password_hash, 3, 32)
        ) {
            return true;
        }
        return false;
    }

    public function validateOldPassword($attribute, $params)
    {
        if (!$this->hasErrors() && !$this->validatePassword($this->oldpassword)) {
            $this->addError($attribute, 'Old password is incorrect.');
        }
    }

    public function resetPassword()
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($this->passwordnow);
        return $this->save(false);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function beforeSave($insert)
    {
        if (!empty($this->password)) {
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        }
        if ($this->role_id === '') {
            $this->role_id = null;
        }
        $this->lang = 'id';

        // if (isset($this->contact_id)) {
        //     $this->setAttribute('contact_id', $this->contact_id);
        // }

        // if (isset($this->positionid)) {
        //     $this->setAttribute('positionid', $this->positionid);
        // }

        $current_time = date('Y-m-d H:i:s');
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = $current_time;
                $this->created_by = Yii::$app->user->id ?? null;
            } else {
                $this->updated_at = $current_time;
                $this->updated_by = Yii::$app->user->id ?? null;
            }
            return true;
        }
        return false;
    }

    // public function afterFind()
    // {
    //     parent::afterFind();

    //     $this->contact_id = $this->getAttribute('contact_id');
    //     $this->positionid = $this->getAttribute('positionid');
    // }

    /** Relasi **/
    public function getContact()
    {
        return $this->hasOne(Contact::class, ['contact_id' => 'contact_id']);
    }

    public function getRole()
    {
        return $this->hasOne(Enum::class, ['enumno' => 'role_id'])
            ->where(['enumtype' => 'users_roles']);
    }

    public function getStatus()
    {
        return $this->hasOne(Enum::class, ['enumno' => 'status'])
            ->where(['enumtype' => 'users_status']);
    }

    public function getPosition()
    {
        return $this->hasOne(Enum::class, ['enumid' => 'positionid'])
            ->where(['enumtype' => 'position']);
    }

    /** Search **/
    public function search($params)
    {
        $filter = " WHERE 1=1 ";
        if (isset($params['search']) && $params['search'] != "") {
            $filter .= " AND (A.name ilike '%" . str_replace("'", "''", $params['search']) . "%' 
                            or A.email ilike '%" . str_replace("'", "''", $params['search']) . "%')";
        }
        if (isset($params['userid']) && $params['userid'] != "") {
            $filter .= " AND A.userid='" . $params['userid'] . "'";
        }

        $id = "userid";
        $sql = "
            select A.userid,A.name,A.email
            from users A
            $filter ";
        $sqlcount = "SELECT COUNT('" . $id . "') FROM ($sql) as temp";
        $count = Yii::$app->db->createCommand($sqlcount)->queryScalar();

        $pagedata = 5;
        if (isset($params['per-page']) && $params['per-page'] != "") {
            if ($params['per-page'] == "-") {
                $pagedata = 1000;
            } else {
                $pagedata = $params['per-page'];
            }
        }
        return new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'key' => $id,
            'pagination' => [
                'pageSize' => $pagedata,
            ],
            'sort' => [
                'defaultOrder' => ['createdat' => SORT_ASC],
                'attributes' => [
                    $id,
                    'createdat',
                ],
            ]
        ]);
    }

    public function getMenu()
    {
        $modelmenu = [new Usermenu()];

        $sql = "select A.menu_id,L.langtext_en AS menu_name_en,L.langtext_id AS menu_name_id,A.menu_url,A.menu_level,
               coalesce(B.lihat,'0') as lihat,
               coalesce(B.tambah,'0') as tambah,
               coalesce(B.ubah,'0') as ubah,
               coalesce(B.hapus,'0') as hapus,
               coalesce(B.cetak,'0') as cetak,
               coalesce(B.persetujuan,'0') as persetujuan
               from menus A
               LEFT join usermenu B ON A.menu_id=B.menuid AND B.userid::text='-'
               LEFT join lang L ON A.menu_name=L.langno AND L.langtype='extrasidebar'
               where A.menu_id <> 15;";
        $rows = Yii::$app->db->createCommand($sql)->queryAll();
        $hasil = "";
        $i = 0;
        foreach ($rows as $row) {
            $modelmenu[$i] = new Usermenu;
            $modelmenu[$i]->menuid = $row['menu_id'];
            if (Yii::$app->user->identity->lang == 'en') {
                $modelmenu[$i]->menuname = $row['menu_name_en'];
            } else {
                $modelmenu[$i]->menuname = $row['menu_name_id'];
            }
            $modelmenu[$i]->lihat = $row['lihat'];
            $modelmenu[$i]->tambah = $row['tambah'];
            $modelmenu[$i]->ubah = $row['ubah'];
            $modelmenu[$i]->hapus = $row['hapus'];
            $modelmenu[$i]->cetak = $row['cetak'];
            $modelmenu[$i]->persetujuan = $row['persetujuan'];
            $i++;
        }

        return $modelmenu;
    }

    public function getAccess($module)
    {
        if ($this->role_id == 0) {
            return [
                'lihat' => 1,
                'tambah' => 1,
                'ubah' => 1,
                'hapus' => 1,
                'cetak' => 1,
                'persetujuan' => 1,
            ];
        }
        $sql = "
        select 
        coalesce(a.lihat,'0') as lihat,
        coalesce(a.tambah,'0') as tambah,
        coalesce(a.ubah,'0') as ubah,
        coalesce(a.hapus,'0') as hapus,
        coalesce(a.cetak,'0') as cetak,
        coalesce(a.persetujuan,'0') as persetujuan
        from usermenu a 
        left join menus m on m.menu_id = a.menuid
        where a.userid = '" . $this->userid . "' and m.menu_module = '" . $module . "'
        ";

        $rows = Yii::$app->db->createCommand($sql)->queryAll();
        return $rows;
    }
}

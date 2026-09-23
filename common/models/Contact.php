<?php

namespace common\models;

use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use common\models\GlobalFunction;
use Yii;
use common\models\Enum;

class Contact extends \yii\db\ActiveRecord
{
    public $npwpfiletemp;
    public $npwpfiletemp2;
    public $npwpfiletemp3;
    public $idfiletemp;
    public $contactphototemp;
    public $nametagfiletemp;
    // public $userid;
    /**
     * {@inheritdoc}
     * @property string $nppfile
     * @property $npwpfiletemp
     */
    public static function tableName()
    {
        return 'contacts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contact_name'], 'required', 'message' => 'Name Required!'],
            [
                [
                    'contact_id',
                    'contact_no',
                    'contact_name',
                    'contact_name2',
                    'contact_name3',
                    'contact_bop',
                    'contact_typeno',
                    'contact_phone1',
                    'contact_phone2',
                    'contact_phone3',
                    'contact_phone4',
                    'contact_email1',
                    'contact_email2',
                    'contact_email3',
                    'contacttype',
                    'positionid',
                    'levelid',
                    'divisionid',
                    'contractid',
                    "contactphoto",
                    "bankaccount_no",
                    "bankname",
                    "extrainfo",
                    "parentname",
                    "parentaddress",
                    "relativename",
                    "relativephone",
                    "relativerelation",
                    "npwpno",
                    "npwpfile",
                    "npwpfile2",
                    "npwpfile3",
                    "bpjstkno",
                    "bpjstktype",
                    "bpjs_kesno",
                    "bpjs_kestype",
                    "insurancename",
                    "insuranceother",
                    "contactnote",
                    'billaddress',
                    'billcountry',
                    'billstate',
                    'billcity',
                    'billdistrict',
                    'billsubdistrict',
                    'billzip',
                    'type',
                    'businesscategory',
                    'website',
                    'nametagfile',
                    "idfile",
                    'status_register',
                    'refid'
                ],
                'string'
            ],
            [['contact_bod', 'jobstart', 'jobend'], 'date'],
            [['contact_bop', 'contact_status'], 'safe'],
            ['contact_typeid', 'each', 'rule' => ['string']],
            [['countryid', 'stateid', 'cityid', 'districtid', 'subdistrictid', 'address', 'zip', 'jobcompany', 'idtype', 'idnumber', 'jobposition', 'jobposition2', 'jobposition3', 'jobid', 'contactbirth', 'refid'], 'string'],
            [['contact_education', 'personid', 'contact_gender', 'contact_religion', 'contact_married', 'userid', 'contact_no', 'payment_status', 'payment_method'], 'safe'],
            [['npwpfiletemp', 'idfiletemp', 'contactphototemp', 'nametagfiletemp'], 'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, jpeg, png, pdf'],
            [['createdby', 'createdip', 'createdat', 'updatedby', 'updatedip', 'updatedat'], 'safe'],
            [['contact_id'], 'unique', 'message' => 'Contact ID is registered'],
            [['contact_name'], 'validateUniqueNamesInCompany'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'contact_id' => 'ID',
            'contact_no' => Yii::$app->lang->t('contact', 'contact_no'),
            'contact_name' => Yii::$app->lang->t('contact', 'contact_name'),
            'contact_typeid' => Yii::$app->lang->t('contact', 'contact_typeid'),
            'contact_gender' => Yii::$app->lang->t('contact', 'contact_gender'),
            'contact_married' => Yii::$app->lang->t('contact', 'contact_married'),
            'contact_phone1' => Yii::$app->lang->t('contact', 'contact_phone1'),
            'contact_religion' => Yii::$app->lang->t('contact', 'contact_religion'),
            'contact_education' => Yii::$app->lang->t('contact', 'contact_education'),
            'contact_phone2' => 'No. WA',
            'contact_email1' => Yii::$app->lang->t('contact', 'contact_email1'),
            'contact_email2' => 'Email 2',
            'address' => Yii::$app->lang->t('contact', 'address'),
            'zip' => Yii::$app->lang->t('contact', 'zip'),
            'jobposition' => Yii::$app->lang->t('contact', 'jobposition'),
            'jobcompany' => Yii::$app->lang->t('contact', 'jobcompany'),
            'idtype' => Yii::$app->lang->t('contact', 'idtype'),
            'idnumber' => Yii::$app->lang->t('contact', 'idnumber'),
            'stateid' => Yii::$app->lang->t('contact', 'state'),
            'countryid' => Yii::$app->lang->t('contact', 'country'),
            'cityid' => Yii::$app->lang->t('contact', 'city'),
            'districtid' => Yii::$app->lang->t('contact', 'district'),
            'subdistrictid' => Yii::$app->lang->t('contact', 'subdistrictid'),
            'contact_bod' => Yii::$app->lang->t('contact', 'contact_bod'),
            'jobstart' => Yii::$app->lang->t('contact', 'jobstart'),
            'jobend' => Yii::$app->lang->t('contact', 'jobend'),
            'datebirth' => Yii::$app->lang->t('contact', 'datebirth'),
            'youpayable' => Yii::$app->lang->t('contact', 'youpayable'),
            'theypayable' => Yii::$app->lang->t('contact', 'theypayable'),

            'contact_bop' => Yii::$app->lang->t('contact', 'contact_bod'),
            'contact_typeno' => "",
            'contacttype' => "",
            'positionid' => Yii::$app->lang->t('position', 'label1'),
            'levelid' => Yii::$app->lang->t('level', 'label1'),
            'divisionid' => Yii::$app->lang->t('contact', 'devision'),
            'contractid' => Yii::$app->lang->t('status', 'label1'),
            "contactphoto" => Yii::$app->lang->t('contact', 'contactphoto'),
            "nametagfile" => Yii::$app->lang->t('contact', 'nametag'),
            "bankaccount_no" => Yii::$app->lang->t('contact', 'norek'),
            "bankname" => Yii::$app->lang->t('contact', 'bank'),
            "extrainfo" => '',
            "parentname" => '',
            "parentaddress" => '',
            "relativename" => '',
            "relativephone" => '',
            "relativerelation" => Yii::$app->lang->t('contact', 'relativerelation'),
            "npwpno" => Yii::$app->lang->t('contact', 'nonpwp'),
            "npwpfile" => Yii::$app->lang->t('contact', 'filenpwp'),
            "bpjstno" => Yii::$app->lang->t('contact', 'bpjstk'),
            "bpjstktype" => Yii::$app->lang->t('contact', 'bpjstktype'),
            "bpjs_kesno" => Yii::$app->lang->t('contact', 'bpjskes'),
            "bpjs_kestype" => Yii::$app->lang->t('contact', 'bpjskestype'),
            "insurancename" => Yii::$app->lang->t('contact', 'asuransi'),
            "insuranceother" => Yii::$app->lang->t('contact', 'asuransilain'),
            "contactnote" => Yii::$app->lang->t('tran', 'tran_note'),
            "idfile" => Yii::$app->lang->t('contact', 'idfile'),
            'personid' => Yii::$app->lang->t('contact', 'personid'),
            'businesscategory' => Yii::$app->lang->t('contact', 'personid'),
            'type' => Yii::$app->lang->t('produk_table', 'produk_jenis'),
            'billingaddress' => Yii::$app->lang->t('contact', 'personid')

        ];
    }

    public function search($params)
    {
        $filter = "where 1=1 ";

        // $lang= strtlower(yii::$app->language);
        $id = "contact_id";
        $sql = "select A.contact_id,A.contact_name,A.contact_no
        from contacts a
        $filter ";
        $sqlcount = "SELECT COUNT('" . $id . "') FROM ($sql) as temp";

        $count = Yii::$app->db->createCommand($sqlcount)->queryScalar();

        $pagedata = 10;
        if (isset($params['per-page']) && $params['per-page'] != "") {
            if ($params['per-page'] == "-") {
                $pagedata = 1000;
            } else {
                $pagedata = $params['per-page'];
            }
        }
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count,
            'key' => $id,
            'pagination' => [
                'pageSize' => $pagedata,
            ],
            'sort' => [
                'defaultOrder' => ['createdat' => SORT_ASC],
                'attributes' => [
                    " . $id . ",
                    'createdat',
                    'contacttype',
                ],
            ]
        ]);
        return $dataProvider;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $userId = Yii::$app->user->id ?? '';
            $userIp = Yii::$app->request->userIP;
            $now = date('Y-m-d H:i:s');
            $this->contact_bod = Yii::$app->function->datePostgresTZ($this->contact_bod);
            $this->jobstart = Yii::$app->function->datePostgresTZ($this->jobstart);
            $this->jobend = Yii::$app->function->datePostgresTZ($this->jobend);
            if ($this->isNewRecord) {
                $this->contact_id = Yii::$app->db->createCommand('SELECT uuid_generate_v4()')->queryScalar();
                $this->contact_status = 1;
                $this->status_register = 0;
                $this->createdat = $now;
                $this->createdip = $userIp;
                $this->createdby = $userId;
            }

            // Selalu update ini
            $this->updatedat = $now;
            $this->updatedip = $userIp;
            $this->updatedby = $userId;

            return true;
        }
        return false;
    }

    public static function nextNo($contacttype)
    {
        $kode = '';
        if ($contacttype == 'customer') {
            $kode = 'MBR';
        } else if ($contacttype == 'employee') {
            $kode = 'EM';
        }

        try {
            $sql = "SELECT COALESCE(MAX(RIGHT(contact_no, 4)::int), 0) + 1 AS no
                FROM contacts
                WHERE contact_status <> '10' 
                AND contact_no LIKE '$kode-%'";

            $results = Yii::$app->db->createCommand($sql)->queryAll();
            // echo$sql;exit;
            $nextKode = $results[0]['no'] ?? 1;

            $fullKode = $kode . '-' . str_pad($nextKode, 4, "0", STR_PAD_LEFT);

        } catch (\Exception $e) {
            $fullKode = $kode . '-0001';
        }

        return $fullKode;
    }
    public function getUser()
    {
        return $this->hasOne(User::class, ['userid' => 'userid']);
    }

    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['refid' => 'contact_id'])
            ->where(['documenttype' => 'contact'])
            ->orderBy(['ord' => SORT_ASC]);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['productid' => 'personid']);
    }

    public function validateUniqueNamesInCompany($attribute, $params)
    {
        $names = array_filter([
            trim((string) $this->contact_name),
            trim((string) $this->contact_name2),
            trim((string) $this->contact_name3),
        ], 'strlen');

        if (empty($names) || empty($this->jobcompany)) {
            return;
        }

        $db = Yii::$app->db;
        $company = $db->quoteValue(trim((string) $this->jobcompany));
        $nameConditions = [];

        foreach ($names as $name) {
            $quotedName = $db->quoteValue($name);
            $nameConditions[] = "(contact_name = {$quotedName} OR contact_name2 = {$quotedName} OR contact_name3 = {$quotedName})";
        }

        $whereName = implode(" OR ", $nameConditions);

        $sql = "SELECT COUNT(*) FROM contacts 
        WHERE jobcompany = {$company} 
          AND contact_status <> '10' 
          AND ({$whereName})";

        if (!$this->isNewRecord && !empty($this->contact_id)) {
            $id = $db->quoteValue($this->contact_id);
            $sql .= " AND contact_id <> {$id}";
        }

        $exists = $db->createCommand($sql)->queryScalar();

        if ($exists > 0) {
            $this->addError($attribute, 'Nama kontak sudah ada di perusahaan ini. Silakan gunakan nama lain.');
        }
    }
}
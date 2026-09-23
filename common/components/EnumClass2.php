<?php

namespace common\components;

// use common\models\Toko;
use yii\helpers\Url;
use Yii;

class EnumClass extends \yii\base\Component
{

    public function init()
    {

        date_default_timezone_set("Asia/Jakarta");
        parent::init();
    }

    public static function isakses($modulename, $kolom = 'lihat')
    {
        if (Yii::$app->user->identity->role_id == '0') {
            return true;
        } else {
            $nilai = "";
            $sql = "
        select a.$kolom as nilai
                from usermenu a
                inner join users b on b.userid=a.userid
				inner join menus c on a.menuid=c.menu_id
                where a.userid='" . Yii::$app->user->identity->userid . "' and c.menu_module='$modulename'
				and b.status <> 10 limit 1";
            // echo $sql; die();
            $nilai = \Yii::$app->db->createCommand($sql)->queryScalar();

            return $nilai == 1;
        }

    }

    public static function isaksesurl($kolom)
    {
        $url = Yii::$app->request->url;

        if (preg_match('#/([^/]+)/([^/\?]+)#', $url, $matches)) {
            $module = $matches[1];
            $type = $matches[2];
        }

       
        $types = $module . $type;

        return Yii::$app->enum->isakses($types, $kolom);
        
    }
    public static function isaksestype($type, $kolom)
    {
        $params = Yii::$app->request->queryParams;
        $module = $params[$type];

        return Yii::$app->enum->isakses($module, $kolom);
        
    }
    public function e($id)
    {
        // var_dump(Yii::$app->language);exit;
        $lang = strtolower(Yii::$app->language);
        $result = $id;
        //var_dump($id);exit;
        if (true) {
            $path_lang = Yii::getAlias('@common') . '/json/enum.json';
            if (!file_exists($path_lang)) {
                return $result;
            }
            // var_dump($path_lang); die;

            $db_lang = file_get_contents($path_lang);

            $json = json_decode($db_lang);

            foreach ($json as $item) {
                if ($item->id == $id) {
                    if ($lang == "id") {
                        $result = $item->enumtext_id;
                    } else {
                        $result = $item->enumtext_en;
                    }
                }
            }
        } else {
            $sql = "select enumtext_" . $lang . " as result from enum where enumid='" . $id . "' limit 1";

            // echo $sql;exit;
            $rows = Yii::$app->db->createCommand($sql)->queryAll();
            $result = $param;
            foreach ($rows as $row) {
                $result = $row['result'];
            }
        }
        return $result;
    }
    public static function getenum($param, $value)
    {
        $result = "";

        if ($param == "grup") {
            if ($value == "superadmin")
                return 100;
            if ($value == "admin")
                return 1;
            if ($value == "manager")
                return 2;
            if ($value == "supervisor")
                return 3;
            if ($value == "hrd")
                return 4;
            if ($value == "akunting")
                return 5;
            if ($value == "gudang")
                return 6;
            if ($value == "kasir")
                return 7;
            if ($value == "salesman")
                return 8;
        }

        if ($param == "jenis") {
            if ($value == "pengeluaran")
                return 0;
            if ($value == "penerimaan")
                return 1;
        }

        if ($param == "sumber") {
            if ($value == "sales")
                return 0;
            if ($value == "purchase")
                return 1;
            if ($value == "kas")
                return 2;
        }
        return $result;
    }



    public static function getuser($param)
    {


        if (Yii::$app->user->identity->grup == Yii::$app->enum->getenum("grup", "superadmin") || Yii::$app->user->identity->grup == Yii::$app->enum->getenum("grup", "admin")) {
            if ($param == "salesmannama") {
                return "INDOLAND";
            } else if ($param == "hp") {
                return "08116686618";
            } else if ($param == "salesmanid") {
                return "151ddb6c-99f9-47b9-a2cf-a83a16875091";
            }
        }

        $query = " 
select A.$param from salesman A
inner join users B ON A.userid=B.userid
where B.userid='" . Yii::$app->user->identity->userid . "'";

        //echo $query;exit;
        return \Yii::$app->db->createCommand($query)->queryScalar();
    }


    public static function isadmin()
    {
        // var_dump(Yii::$app->user->identity->role_id);
        // exit;
        if (Yii::$app->user->identity->role_id == 0 || Yii::$app->user->identity->role_id == 1) {
            return true;
        }
        return false;
    }

    public static function issuperadmin()
    {
        // var_dump(Yii::$app->user->identity->userid);
        // exit;
        if (Yii::$app->user->identity->level == (Yii::$app->enum->getenum("grup", "superadmin"))) {
            return true;
        }
        return false;
    }

    public static function getgrupvw()
    {
        if (\Yii::$app->user->identity->role_id == "") {
            return "";
        }
        $grupname = Yii::$app->function->findByField("enum_name", "enums", " AND enum_type='users_role' AND enum_no='" . \Yii::$app->user->identity->role_id . "'");
        return ucfirst($grupname);
    }


    public static function getinitial()
    {
        $words = preg_split("/\s+/", Yii::$app->user->identity->nama);

        $initial = strtoupper(substr(($words[0]), 0, 1)) . strtoupper(substr(($words[1]), 0, 1));
        return $initial;
    }

    public static function getavatar()
    {
        //$path = '/' . \Yii::$app->enum->getparentid();
        $hasil = Yii::$app->urlBackend->baseUrl . '/images/user.png';

        if (Yii::$app->user->identity->avatar != null) {
            //echo (Yii::$app->user->identity->avatar);
            //var_dump(Yii::$app->user->identity->avatar);exit;
            $hasil = Yii::$app->urlBackend->baseUrl . "/uploads/" . Yii::$app->enum->getparentid() . "/medium/" . Yii::$app->user->identity->avatar;

            if (strpos(Yii::$app->user->identity->avatar, 'google') > 0 || strpos(Yii::$app->user->identity->avatar, 'insta') > 0 || strpos(Yii::$app->user->identity->avatar, 'face') > 0) {
                $hasil = Yii::$app->user->identity->avatar;
            }
        }

        return $hasil;
    }



    public static function getparentpath($param)
    {
        $uploadPathUser = Yii::getAlias('@backend') . '/web/uploads/' . \Yii::$app->enum->getparentid();
        $uploadPathThumb = Yii::getAlias('@backend') . '/web/uploads/' . \Yii::$app->enum->getparentid() . '/thumb/';
        $uploadPathMedium = Yii::getAlias('@backend') . '/web/uploads/' . \Yii::$app->enum->getparentid() . '/medium/';
        $uploadPathOri = Yii::getAlias('@backend') . '/web/uploads/' . \Yii::$app->enum->getparentid() . '/ori/';
        $uploadPathPdf = Yii::getAlias('@backend') . '/web/uploads/' . \Yii::$app->enum->getparentid() . '/pdf/';
        $uploadPathMark = Yii::getAlias('@backend') . '/web/uploads/' . \Yii::$app->enum->getparentid() . '/mark/';
        $uploadPathKomen = Yii::getAlias('@backend') . '/web/uploads/' . \Yii::$app->enum->getparentid() . '/komen/';

        //echo $uploadPathKomen;exit;
        if (!file_exists($uploadPathUser)) {
            (mkdir($uploadPathUser, 0777, true));
        }
        if (!file_exists($uploadPathOri)) {
            (mkdir($uploadPathOri, 0777, true));
        }

        if (!file_exists($uploadPathThumb)) {
            (mkdir($uploadPathThumb, 0777, true));
        }

        if (!file_exists($uploadPathThumb)) {
            (mkdir($uploadPathThumb, 0777, true));
        }


        if (!file_exists($uploadPathMedium)) {
            (mkdir($uploadPathMedium, 0777, true));
        }

        if (!file_exists($uploadPathMark)) {
            (mkdir($uploadPathMark, 0777, true));
        }

        if (!file_exists($uploadPathKomen)) {
            (mkdir($uploadPathKomen, 0777, true));
        }

        if ($param == "user") {
            return $uploadPathUser;
        } else if ($param == "thumb") {
            return $uploadPathThumb;
        } else if ($param == "medium") {
            return $uploadPathMedium;
        } else if ($param == "ori") {
            return $uploadPathOri;
        } else if ($param == "mark") {
            return $uploadPathMark;
        } else if ($param == "pdf") {
            return $uploadPathPdf;
        } else if ($param == "komen") {
            return $uploadPathKomen;
        } else {
            return "";
        }
    }
    public static function gettoko($param)
    {
        $hasil = "";
        $toko = null;
        $path = '/' . \Yii::$app->enum->getparentid();

        //var_dump(SERVERURL) ;exit;
        if (SERVERURL == "seller.jualanonline.id") {
            if (Yii::$app->session->get('tokoid') != null) {
                $tokoid = Yii::$app->session->get('tokoid');
            }
        } else if (SERVERURL == "localhost") {
            $query = "SELECT tokoid as result FROM toko where status<>10 and url='anitashop.jualanonline.id' limit 1";
            $tokoid = \Yii::$app->db->createCommand($query)->queryScalar();
            Yii::$app->session->set('tokoid', $tokoid);
        } else {
            $query = "SELECT tokoid as result FROM toko where status<>10 and lower(url)='" . strtolower(SERVERURL) . "' limit 1";
            //echo $query;exit;
            $tokoid = \Yii::$app->db->createCommand($query)->queryScalar();
            Yii::$app->session->set('tokoid', $tokoid);
        }


        $toko = Toko::findOne($tokoid);
        //var_dump ($toko); exit;
        if ($param == "logo") {
            $hasil = Yii::$app->urlBackend->baseUrl . '/images/default.png';
            if ($toko->logo != null && $toko->logo != "") {
                $hasil = Yii::$app->enum->gettoko("urlpath") . $toko->logo;
                //  $hasil = Yii::$app->urlBackend->baseUrl . $path . $toko->logo;
            }
        } else if ($param == "urlpath") {
            //	var_dump ($toko->userid);

            if ($tokoid == "") {
                $hasil = Yii::$app->urlBackend->baseUrl . "/uploads/" . Yii::$app->enum->getparentid() . "/medium/";
            } else {
                $hasil = Yii::$app->urlBackend->baseUrl . "/uploads/" . $toko->userid . "/medium/";
            }
            //Yii::$app->user->identity->userid
        } else if ($param == "urlthumb") {
            if ($tokoid == "") {
                $hasil = Yii::$app->urlBackend->baseUrl . "/uploads/" . Yii::$app->enum->getparentid() . "/thumb/";
            } else {
                $hasil = Yii::$app->urlBackend->baseUrl . "/uploads/" . $toko->userid . "/thumb/";
            }
        } else if ($param == "urlori") {
            if ($tokoid == "") {
                $hasil = Yii::$app->urlBackend->baseUrl . "/uploads/" . Yii::$app->enum->getparentid() . "/ori/";
            } else {
                $hasil = Yii::$app->urlBackend->baseUrl . "/uploads/" . $toko->userid . "/ori/";
            }
        } else if ($param == "urlkomen") {
            if ($tokoid == "") {
                $hasil = Yii::$app->urlBackend->baseUrl . "/uploads/" . Yii::$app->enum->getparentid() . "/komen/";
            } else {
                $hasil = Yii::$app->urlBackend->baseUrl . "/uploads/" . $toko->userid . "/komen/";
            }
        } else if ($param == "logopath") {
            $hasil = '/images/default.png';

            if ($tokoid == "") {
                $hasil = "/uploads/" . Yii::$app->enum->getparentid() . "/thumb/" . $toko->logo;
            } else {
                $hasil = "/uploads/" . $toko->userid . "/thumb/" . $toko->logo;
            }
        } else if ($param == "banner") {
            $hasil = Yii::$app->urlBackend->baseUrl . '/images/default.png';
            if ($toko->logo != null && $toko->logo != "") {
                $hasil = Yii::$app->urlBackend->baseUrl . $path . $toko->logo;
            }
        } else if ($param == "nama") {
            if ($toko->tokonama != null && $toko->tokonama != "") {
                $hasil = $toko->tokonama;
            }
        } else if ($param == "alamat") {
            if ($toko->alamat != null && $toko->alamat != "") {
                $hasil = $toko->alamat;
            }
        } else if ($param == "kecamatanid") {
            if ($toko->kecamatanid != null && $toko->kecamatanid != "") {
                $hasil = $toko->kecamatanid;
            }
        } else if ($param == "lokasi") {
            if ($toko->kecamatanid != null && $toko->kecamatanid != "") {


                $hasil = Yii::$app->enum->getlokasi($toko->kecamatanid);
            }
        } else if ($param == "tokoid") {
            $hasil = Yii::$app->session->get('tokoid');
        } else if ($param == "email") {
            $hasil = $toko->email;
        } else if ($param == "domain") {
            $hasil = $toko->url;
        } else if ($param == "phone") {
            $hasil = $toko->phone;
            //  var_dump($toko);exit;
        } else if ($param == "deskripsi") {
            $hasil = $toko->deskripsi;
        } else if ($param == "fb") {
            $hasil = $toko->fb;
        } else if ($param == "ig") {
            $hasil = $toko->ig;
        } else if ($param == "slogan") {
            $hasil = $toko->slogan;
        } else if ($param == "theme") {
            $hasil = $toko->theme;
        } else if ($param == "themeopt") {
            $toko = Toko::findOne($tokoid);
            //	var_dump($toko->userid);exit;
            if ($toko->theme == "default") {
                $hasil = "1";
            } else {
                $hasil = str_replace("default", "", $toko->theme);
            }
        } else if ($param == "url") {
            $hasil = $toko->url;
        } else if ($param == "paket") {
            $hasil = $toko->paket;
        } else if ($param == "expired") {
            $hasil = $toko->expired;
        } else if ($param == "isdomain") {
            $hasil = $toko->isdomain;
        } else if ($param == "analytic") {
            $hasil = $toko->analytic;
        } else if ($param == "fbpixel") {
            $hasil = $toko->fbpixel;
        } else if ($param == "isauto") {
            $hasil = $toko->isauto;
        }
        return $hasil;
    }

    public static function getlokasi($id)
    {
        if ($id == "")
            return "-";
        $hasil = "";
        $sql = " 
select lokasivw as result from vwlokasi where kecamatanid='" . $id . "'";
        $results = \Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($results as $result) {
            $hasil = $result['result'] == null ? "" : $result['result'];
        }
        return $hasil;
    }

    public static function getlogo()
    {
        $hasil = Yii::$app->urlBackend->baseUrl . '/images/baru.png';
        $toko = null;
        if (Yii::$app->session->get('tokoid') != null) {
            $toko = Toko::find()->where(['userid' => Yii::$app->user->identity->userid])->one();
        }

        if ($toko != null) {
            $hasil = Yii::$app->urlBackend->baseUrl . $toko->logo;
        }

        return $hasil;
    }

    public static function getthumbnail($logo)
    {
        $hasil = Yii::$app->urlBackend->baseUrl . '/images/default.png';
        if ($logo != null) {
            $hasil = Yii::$app->enum->gettoko("urlthumb") . $logo;
        }

        return $hasil;
    }

    public static function getori($logo)
    {
        $hasil = Yii::$app->urlBackend->baseUrl . '/images/default.png';
        if ($logo != null) {
            $hasil = Yii::$app->enum->gettoko("urlori") . $logo;
        }

        return $hasil;
    }

    public static function getmedium($logo)
    {
        $hasil = Yii::$app->urlBackend->baseUrl . '/images/default.png';
        if ($logo != null) {
            $hasil = Yii::$app->enum->gettoko("urlmedium") . $logo;
        }

        return $hasil;
    }

    public static function getkomen($logo)
    {
        $hasil = Yii::$app->urlBackend->baseUrl . '/images/default.png';
        if ($logo != null) {
            $hasil = Yii::$app->enum->gettoko("urlkomen") . $logo;
        }

        return $hasil;
    }


    public static function getthumbnailadd($logo)
    {
        $hasil = Yii::$app->urlBackend->baseUrl . '/images/empty.png';
        if ($logo != null) {

            $hasil = Yii::$app->enum->gettoko("urlpath") . $logo;
        }

        return $hasil;
    }

    public static function getparentid()
    {
        return Yii::$app->user->identity->userid;
        if (Yii::$app->user->identity->parentid == null) {
            if (Yii::$app->user->identity->userid == null) {
                return Yii::$app->session->get('userid');
            } else {
                return Yii::$app->user->identity->userid;
            }
        } else {
            return Yii::$app->user->identity->parentid;
        }
    }

    public static function getthumb($logo)
    {
        $hasil = Yii::$app->urlBackend->baseUrl . '/images/empty.png';
        if ($logo != null) {
            $hasil = Yii::$app->urlBackend->baseUrl . '/uploads/' . $logo;
        }
        return $hasil;
    }
}

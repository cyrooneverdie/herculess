<?php

namespace common\components;

use Yii;
use JavaClass;
use Java;
use common\models\Permintaan;

class FunctionClass extends \yii\base\Component
{

    public function init()
    {

        date_default_timezone_set("Asia/Jakarta");

        /* 	if (\Yii::$app->getUser()->isGuest &&
          \Yii::$app->getRequest()->url !== Url::to(\Yii::$app->getUser()->loginUrl)
          ) {
          \Yii::$app->getResponse()->redirect(\Yii::$app->getUser()->loginUrl);
          } */
        parent::init();
    }
    public function Createqr($id, $total)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.xendit.co/qr_codes');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "external_id=$id&type=DYNAMIC&callback_url=https://seller.jualanonline.id/api/qrpaid&amount=$total");
        curl_setopt($ch, CURLOPT_USERPWD, 'xnd_production_bAnFVVLka7K55hOwKHXNCbOQxpNQpPBc0kpJc6Uv2x4TT6ap2DpfJaCPH0YSf' . ':' . '');

        $headers = array();
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }
    public function Checkqr($id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.xendit.co/qr_codes/payments?external_id=$id&limit=2");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_USERPWD, 'xnd_production_bAnFVVLka7K55hOwKHXNCbOQxpNQpPBc0kpJc6Uv2x4TT6ap2DpfJaCPH0YSf' . ':' . '');

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }
    public function generateJson($filename, $rows)
    {
        $path = Yii::getAlias('@common') . '/json/';

        $json = json_encode($rows);
        $filepath = $path . $filename;
        // var_dump($filepath); die;
        $fp = fopen($filepath, 'w+');
        fwrite($fp, $json);
        fclose($fp);
        var_dump($json);
    }
    public function insertBarang($refid, $jenis)
    {

        $cekbarang = "select B.sku,A.produkid,sum(A.qty) as qty,B.hargabeli as harga from pesanandetail A
				LEFT JOIN produk B ON A.produkid = B.produkid
				where A.pesananid ='$refid' 
				Group by A.produkid,B.sku,B.hargabeli";
        var_dump($cekbarang);
        exit;
        $barang = Yii::$app->db->createCommand($cekbarang)->queryAll();
        $barang = $barang[0];
        $sku = $barang['sku'];
        $produkid = $barang['produkid'];
        $harga = $barang['harga'];
        $qty = $barang['qty'];
        // JENIS
        // 1 PRE ORDER
        // 2 RETUR
        $text = "";
        if ($jenis == 1) {
            $text = "Pre Order Barang " . $sku;
            $pemasokid = "22c9e8b8-c12d-4dec-b90b-f0886ee5f284";
        } else {

            $text = "Retur barang " . $sku . " Dari ";
            $pemasokid = "4a1d94c4-2e35-4c79-bc1f-b973cb72e99e";
        }

        $idpembelian = \Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
        $permintaanno = Permintaan::nextNo();
        if ($staffid == "" || $staffid == NULL) {
            $staffid = "null";
        } else {
            $staffid = \Yii::$app->enum->getstaff("id");
            $staffid = "'" . $staffid . "'";
        }
        //var_dump ($staffid); exit;

        $tgladd = Yii::$app->function->standard_date('DATE_RFC822', time());
        $pcadd = $_SERVER['REMOTE_ADDR'];
        $opadd = \Yii::$app->user->id;



        $permintaanid = Yii::$app->function->findByField("permintaanid", "permintaan", " and catatan = 'Retur barang ' and tokoid='" . \Yii::$app->session->get('tokoid') . "'");

        if ($permintaanid == "") {
            $modelpermintaan = new Permintaan;
            $modelpermintaan->pemasokid = $pemasokid;
            $modelpermintaan->permintaantgl = date("d/m/Y");
            $modelpermintaan->catatan = "DARI MARKETPLACE";
            $modelpermintaan->isimport = 1;
            $modelpermintaan->status = 1;
            $modelpermintaan->save(false);
            $permintaanid = $modelpermintaan->getPrimaryKey();
        }

        $sqlpembelian = "INSERT INTO public.permintaan(
		permintaanid, staffid, pemasokid, 
		permintaanno, permintaantgl, catatan, isimport, status, tokoid, 
		opadd, tgladd, pcadd)
		VALUES ('$idpembelian',$staffid,'$pemasokid',
		'$permintaanno',now()::date,'$text', '0','1','" . Yii::$app->session->get('tokoid') . "',
		'$opadd','$tgladd','$pcadd')";

        Yii::$app->db->createCommand($sqlpembelian)->execute();

        $idpembeliandetail = \Yii::$app->db->createCommand('select uuid_generate_v4()')->queryScalar();
        $sqlpembeliandetail = "INSERT INTO public.permintaandetail(
		permintaandetailid, permintaanid, produkid, qty, harga, discpersen, disc, pajak,  hpp)
		VALUES ('$idpembeliandetail', '$idpembelian', '$produkid', '$qty', $harga, 0, 0, 0,  0);";

        Yii::$app->db->createCommand($sqlpembeliandetail)->execute();
    }
    public static function xenditkey()
    {

        //$api = "xnd_development_xTQYiPlG0vMTyl03UfbZVjEjlulH6Gcb9VIzqcfJSDuDLQT589kxIh9l3raRv"; //TEST
        //$api = "xnd_production_SJijbjvRCh1OxxCE7jJhRTWxOiMVc1OP2UCG2nwjzlECTO4vaBQTuyyT75u2H3S"; //LIVE 
        //$api = "xnd_production_PCVGVXF2lO7RYIr1jE55wbHokxzKYPnynK5H4P6IojDpzXMEsQPf99CRcbAJ6L"; //LIVE 
        $api = "xnd_production_bAnFVVLka7K55hOwKHXNCbOQxpNQpPBc0kpJc6Uv2x4TT6ap2DpfJaCPH0YSf"; //LIVE 

        return $api;
    }
    public static function array_cartesian_utm($arrays)
    {

        //returned array...
        $cartesic = array();

        //calculate expected size of cartesian array...
        $size = (sizeof($arrays) > 0) ? 1 : 0;
        foreach ($arrays as $array) {
            $size = $size * sizeof($array);
        }
        for ($i = 0; $i < $size; $i++) {
            $cartesic[$i] = array();

            for ($j = 0; $j < sizeof($arrays); $j++) {
                $current = current($arrays[$j]);
                array_push($cartesic[$i], $current);
            }
            //set cursor on next element in the arrays, beginning with the last array
            for ($j = (sizeof($arrays) - 1); $j >= 0; $j--) {
                //if next returns true, then break
                if (next($arrays[$j])) {
                    break;
                } else {    //if next returns false, then reset and go on with previuos array...
                    reset($arrays[$j]);
                }
            }
        }
        return $cartesic;
    }
    // public static function smsSend($to, $sender, $content,  $template = "", &$issend, &$log)
    // {

    //     $hp = $to;
    //     $text = $content;

    //     //	$text = "Hi $line,\nBuat Toko Online Pribadi Ternyata Hanya Butuh 1 Menit! Coba Gratis Sekarang di \n\nhttps://seller.jualanonline.id";

    //     $senderid = $sender;
    //     $sql = "insert into outbox (\"DestinationNumber\",\"TextDecoded\",\"SenderID\",\"CreatorID\")
    // 	values ('$hp','$text','$senderid','$senderid')";
    //     //var_dump($sql);exit;
    //     $flag = Yii::$app->db->createCommand($sql)->execute();

    //     $issend = true;
    //     $log = "Pending Outbox";
    // }


    public static function mailSend($mail_from, $mail_from_name, $mail_pass, $host, $port, $encryption, $mail_to, $override_subject, $overide_content, $template, &$issend, &$logemail)
    {
        //echo "berhasil"; exit;
        //return;
        $mail_from = trim($mail_from);


        if ($mail_from_name != "") {
            //$mail_from = array($mail_from => $mail_from_name);
            //echo "sukses"; exit;
        }
        //var_dump($mail_from_name);exit;
        $mail_pass = $mail_pass;
        $host = $host;
        $port = $port;
        $encryption = $encryption;

        $mail_to = trim($mail_to);
        //var_dump($mail_to);exit;
        $subject = $override_subject;
        $html_body = $overide_content;

        $config =
            [
                'class' => 'yii\swiftmailer\Mailer',
                'viewPath' => '@common/mail',
                'useFileTransport' => false, //set this property to false to send mails to real email addresses
                //comment the following array to send mail using php's mail function
                'transport' => [
                    'class' => 'Swift_SmtpTransport',
                    'host' => $host,
                    'username' => $mail_from,
                    'password' => $mail_pass,
                    'port' => $port,
                    'encryption' => $encryption,
                ],
            ];


        $sendObj = Yii::createObject($config);

        try {
            //var_dump($mail_from);exit;
            $result = $sendObj->compose()
                ->setFrom(array("$mail_from" => "$mail_from_name"))
                ->setTo($mail_to)
                ->setSubject($subject)
                ->setHtmlBody($html_body)
                ->send();
            //var_dump($mail_from_name);exit;
            //echo 'masuk'; exit;
            //var_dump($result);exit;
            /*
                        $result = $sendObj->compose([
                        'html' => '' . $view . '-html',
                        'text' => '' . $view . '-text',
                    ], $params)
                        ->setTo($mail_to)
                        ->setFrom($mail_from)
                        ->setSubject($subject)
                        //->attach($modelpesan->attachment)
                        ->send();*/


            $issend = true;
            $logemail = " Email has been successfully sent";
        } catch (\Swift_TransportException $exception) {

            $issend = false;
            $string = $exception->getMessage();
            $logemail = $string;
            //echo $string;
            //echo 'masuk sini'; exit;
        }

        //var_dump($issend); exit;
        return;
    }


    public static function mailSendMandrill($mail_from, $mail_from_name, $mail_pass, $host, $port, $encryption, $mail_to, $override_subject, $overide_content, $template, &$issend, &$logemail)
    {
        return;
        require(Yii::getAlias('@vendor') . "/mandrill/src/Mandrill.php");

        //  $email = new \SendGrid\Mail\Mail();



        $mail_from = $mail_from;


        if ($mail_from_name != "") {
            //$mail_from = array($mail_from => $mail_from_name);
        }
        //var_dump($mail_from);exit;
        $mail_pass = $mail_pass;
        $host = $host;
        $port = $port;
        $encryption = $encryption;


        $subject = $override_subject;
        $html_body = $overide_content;

        $emails[] = array(
            'email' => $mail_to,
            'type' => 'to'
        );


        try {
            //$mandrill = new Mandrill('kWre_48F1lnJs3_39YM434z');//API KEY
            $mandrill = new \Mandrill($mail_pass);
            $message = array(
                'html' => html_body,
                'subject' => $subject,
                'from_email' => 'info@instagramku.net',
                'from_name' => 'Instagramku',
                'to' => $emails,
                'headers' => array('Reply-To' => 'info@instagramku.net'),
                'important' => false,
                'track_opens' => null,
                'track_clicks' => null,
                'auto_text' => null,
                'auto_html' => null,
                'inline_css' => null,
                'url_strip_qs' => null,
                'preserve_recipients' => false,
                'view_content_link' => null,
                'bcc_address' => $mail_bc,
                'tracking_domain' => null,
                'signing_domain' => null,
                'return_path_domain' => null,
                'merge' => true,
                'merge_language' => 'mailchimp',

            );
            $async = false;
            $ip_pool = 'Main Pool';

            $result = $mandrill->messages->send($message, $async, $ip_pool);
            var_dump($result->status);
            exit;
            $issend = true;
            $logemail = " Email has been successfully sent";
        } catch (Mandrill_Error $exception) {
            $issend = false;
            $string = $exception->getMessage();
            $logemail = 'A mandrill error occurred: ' . get_class($exception) . ' - ' . $exception->getMessage();

            // Mandrill errors are thrown as exceptions
            // echo 'A mandrill error occurred: ' . get_class($exception) . ' - ' . $exception->getMessage();
            // A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
            //  throw $e;
        }
    }


    public static function mailSendGrid($mail_from, $mail_from_name, $mail_pass, $host, $port, $encryption, $mail_to, $override_subject, $overide_content, $template, &$issend, &$logemail)
    {
        return;
        $mail_from = $mail_from;
        //	$mail_from = "info@gerai.co.id";

        if ($mail_from_name != "") {
            //$mail_from = array($mail_from => $mail_from_name);
        }
        //var_dump($mail_from);exit;
        $mail_pass = $mail_pass;
        $host = $host;
        $port = $port;
        $encryption = $encryption;

        $mail_to = $mail_to;
        $subject = $override_subject;
        $html_body = $overide_content;

        $html_text = trim(preg_replace('/\s\s+/', ' ', $html_body));
        $html_text = preg_replace('/\n\r+/', ' ', $html_text);
        $html_text = trim(preg_replace('/\s+/', ' ', $html_text));
        $html_text = preg_replace('~[\r\n]+~', ' ', $html_text);

        $html_text = strip_tags($html_text);

        require(Yii::getAlias('@vendor') . "/sendgrid-php/sendgrid-php.php");
        $SENDGRID_API_KEY = ('SENDGRID_API_KEY'); //$mail_pass;
        //var_dump($SENDGRID_API_KEY);exit;

        $email = new \SendGrid\Mail\Mail();
        //$email->setFrom("info@gerai.co.id", "Jualan Online");
        $email->setFrom("$mail_from", "$mail_from_name");
        $email->setSubject($subject);
        $email->addTo($mail_to);
        /*$email->addContent(
                "text/plain", $html_body
            );*/
        /*$email->addContent(
                "text/html", $html_body
            );*/

        $email->addContent("text/plain", $html_text);
        $email->addContent(
            "text/html",
            $html_body
        );

        //$sendgrid = new \SendGrid($SENDGRID_API_KEY);
        $sendgrid = new \SendGrid($mail_pass);
        //var_dump($email);exit;
        try {
            $response = $sendgrid->send($email);

            var_dump($response);
            exit;
            //	print "<br/>".$response->statusCode() . "\n";
            //	print "<br/>".($response->headers());
            //	print "<br/>".$response->body() . "\n";

            $issend = true;
            $logemail = " Status <br/>" . $response->statusCode() . "<br/>" . $response->body() . "\n";
        } catch (Exception $e) {
            $issend = true;
            $logemail = $e->getMessage();
            //echo "<br/>".'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
    function cleanQuery($sql)
    {
        $sql = trim(preg_replace('/\s\s+/', ' ', $sql));
        $sql = preg_replace('/\n\r+/', ' ', $sql);
        $sql = trim(preg_replace('/\s+/', ' ', $sql));
        $sql = preg_replace('~[\r\n]+~', ' ', $sql);
        return $sql;
    }

    function gethpp($produkid, $tanggal, $waktu)
    {
        $hpp = 0;
        $sql = "select coalesce(gethpp('$produkid','$tanggal'::date,'$waktu'::timestamp),0) as hpp";
        $rows = Yii::$app->db->createCommand($sql)->queryAll();

        foreach ($rows as $row) {
            $hpp = $row['hpp'];
        }
        if ($hpp == null || $hpp == '' || $hpp < 0) {
            $hpp = 0;
        }
        return $hpp;
    }

    function validate_mobile($phoneNumber)
    {
        preg_match('/(0|\+?\d{2})(\d{7,8})/', $phoneNumber, $matches);
        //echo $matches[1] . ' is the extension.' . "\n";
        //echo $matches[2] . ' is the phone number.' . "\n";
        //exit;

        if ($matches[1] == "0" || $matches[1] == "+62") {
            return true;
        }
        return false;
    }

    function validate_email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function getMax($field, $from, $where)
    {

        try {
            //            $filter = "";
            $sql = "select cast(max($field) as bigint)+1 as kode from $from where 1=1 $where";
            $nextKode = "1";
            $results = \Yii::$app->db->createCommand($sql)->queryAll();
            foreach ($results as $result) {
                $nextKode = $result['kode'] == null ? 1 : $result['kode'];
            }
        } catch (Exception $e) {
            $nextKode = 1;
        }
        return $nextKode;
    }

    public static function getToko($column, $url)
    {
        if ($url == "localhost")
            $url = "tokojustin.com";
        $query = "SELECT $column as result FROM toko where status<>10 and url='" . $url . "' limit 1";
        return \Yii::$app->db->createCommand($query)->queryScalar();
    }

    public static function findByField($field, $table, $where = '')
    {
        $sql = "SELECT $field as result FROM $table where 1=1 $where";
        $rows = \Yii::$app->db->createCommand($sql)->queryAll();
        $hasil = "";
        foreach ($rows as $hsl) {
            $hasil = $hsl['result'];
        }
        return $hasil;
    }

    public static function cryptPassword($password)
    {
        $sql = "select crypt('$password',gen_salt('md5'))";
        $rows = \Yii::$app->db->createCommand($sql)->queryAll();
        $result = $rows[0];
        $hasilcrypt = $result['crypt'];
        return $hasilcrypt;
    }

    public static function datePostgres($tanggal)
    {
        $result = null;
        $result = substr($tanggal, 6, 4) . '/' . substr($tanggal, 3, 2) . '/' . substr($tanggal, 0, 2);
        if ($result == "//") {
            return null;
        }
        return $result;
    }

    public static function datePostgresRev($tanggal)
    {
        $result = null;
        $result = substr($tanggal, 8, 2) . '/' . substr($tanggal, 5, 2) . '/' . substr($tanggal, 0, 4);
        if ($result == "//") {
            return null;
        }
        return $result;
    }

    public static function datePostgresTZ($date)
    {
        try {
            $temp = date_create_from_format('d/m/Y', $date);
            $newTZ = new \DateTimeZone("Asia/Jakarta");
            if ($temp) {
                $temp->setTimezone($newTZ);
                $temp = $temp->format('Y-m-d');
                return $temp;
            } else {
                return $date;
            }
        } catch (Exception $e) {
            return null;
        }
    }

    public static function datePostgresTZTime($date)
    {


        try {
            $temp = date_create_from_format('d/m/Y H:i', $date);

            $newTZ = new \DateTimeZone("Asia/Jakarta");
            if ($temp) {
                $temp->setTimezone($newTZ);
                $temp = $temp->format('Y-m-d H:i');
                return $temp;
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }

    public static function setPrint($table, $field, $id, $status)
    {
        try {
            $sql = "UPDATE $table SET isprint='" . $status . "' WHERE $field='" . $id . "'";
            $results = \Yii::$app->db->createCommand($sql)->execute();
            return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    public static function dateNow()
    {
        try {
            $temp = date_create_from_format('d/m/Y', $date);
            $newTZ = new \DateTimeZone("Asia/Jakarta");
            if ($temp) {
                $temp->setTimezone($newTZ);
                $temp = $temp->format('Y-m-d');
                return $temp;
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }

    public static function datetimeNow()
    {
        try {
            $temp = date_create_from_format('d/m/Y', $date);
            $newTZ = new \DateTimeZone("Asia/Jakarta");
            if ($temp) {
                $temp->setTimezone($newTZ);
                $temp = $temp->format('Y-m-d');
                return $temp;
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }

    public static function standard_date($fmt = 'DATE_RFC822', $time = '')
    {
        $formats = array(
            'DATE_SMALL' => '%m/%d/%Y',
            'DATE_ATOM' => '%Y-%m-%dT%H:%i:%s%Q',
            'DATE_COOKIE' => '%l, %d-%M-%y %H:%i:%s UTC',
            'DATE_ISO8601' => '%Y-%m-%dT%H:%i:%s%O',
            'DATE_RFC822' => '%D, %d %M %y %H:%i:%s %O',
            'DATE_RFC850' => '%l, %d-%M-%y %H:%m:%i UTC',
            'DATE_RFC1036' => '%D, %d %M %y %H:%i:%s %O',
            'DATE_RFC1123' => '%D, %d %M %Y %H:%i:%s %O',
            'DATE_RSS' => '%D, %d %M %Y %H:%i:%s %O',
            'DATE_W3C' => '%Y-%m-%dT%H:%i:%s%Q'
        );

        if (!isset($formats[$fmt])) {
            return FALSE;
        }

        return \Yii::$app->function->mdate($formats[$fmt], $time);
    }

    public static function convertMonthIntoSingkat($bulan)
    {
        switch ($bulan) {
            case '1':
                $m = 'JAN';
                break;
            case '2':
                $m = 'FEB';
                break;
            case '3':
                $m = 'MAR';
                break;
            case '4':
                $m = 'APR';
                break;
            case '5':
                $m = 'MAY';
                break;
            case '6':
                $m = 'JUN';
                break;
            case '7':
                $m = 'JUL';
                break;
            case '8':
                $m = 'AUG';
                break;
            case '9':
                $m = 'SEP';
                break;
            case '10':
                $m = 'OCT';
                break;
            case '11':
                $m = 'NOV';
                break;
            case '12':
                $m = 'DES';
                break;
        }
        return $m;
    }

    public static function mdate($datestr = '', $time = '')
    {
        if ($datestr == '')
            return '';

        if ($time == '')
            $time = now();

        $datestr = str_replace('%\\', '', preg_replace("/([a-z]+?){1}/i", "\\\\\\1", $datestr));
        return date($datestr, $time);
    }

    public static function convertMonthIntoRome($bulan)
    {
        switch ($bulan) {
            case '1':
                $m = 'I';
                break;
            case '2':
                $m = 'II';
                break;
            case '3':
                $m = 'III';
                break;
            case '4':
                $m = 'IV';
                break;
            case '5':
                $m = 'V';
                break;
            case '6':
                $m = 'VI';
                break;
            case '7':
                $m = 'VII';
                break;
            case '8':
                $m = 'VIII';
                break;
            case '9':
                $m = 'IX';
                break;
            case '10':
                $m = 'X';
                break;
            case '11':
                $m = 'XI';
                break;
            case '12':
                $m = 'XII';
                break;
        }
        return $m;
    }

    public static function terbilang($uang)
    {
        $rp = \Yii::$app->function->spellNumberInIndonesian($uang);
        echo ucwords($rp . ' rupiah ');
    }

    /*
      sumber http://www.lesantoso.com/terbilang.html
     */

    public static function spellNumberInIndonesian($number)
    {
        // $number = strval($number);
        // //if (!ereg("^[0-9]{1,15}$", $number))   deprecated
        // if (!preg_match("/^[0-9]{1,15}$/", $number))
        //     return (false);
        // $ones = array(
        //     "",
        //     "satu",
        //     "dua",
        //     "tiga",
        //     "empat",
        //     "lima",
        //     "enam",
        //     "tujuh",
        //     "delapan",
        //     "sembilan"
        // );
        // $majorUnits = array("", "ribu", "juta", "milyar", "trilyun");
        // $minorUnits = array("", "puluh", "ratus");
        // $result = "";
        // $isAnyMajorUnit = false;
        // $length = strlen($number);
        // for ($i = 0, $pos = $length - 1; $i < $length; $i++, $pos--) {
        //     if ($number{
        //     $i} != '0') {
        //         if ($number{
        //         $i} != '1')
        //             $result .= $ones[$number{
        //             $i}] . ' ' . $minorUnits[$pos % 3] . ' ';
        //         else if ($pos % 3 == 1 && $number{
        //         $i + 1} != '0') {
        //             if ($number{
        //             $i + 1} == '1')
        //                 $result .= "sebelas ";
        //             else
        //                 $result .= $ones[$number{
        //                 $i + 1}] . " belas ";
        //             $i++;
        //             $pos--;
        //         } else if ($pos % 3 != 0)
        //             $result .= "se" . $minorUnits[$pos % 3] . ' ';
        //         else if ($pos == 3 && !$isAnyMajorUnit)
        //             $result .= "se";
        //         else
        //             $result .= "satu ";
        //         $isAnyMajorUnit = true;
        //     }
        //     if ($pos % 3 == 0 && $isAnyMajorUnit) {
        //         $result .= $majorUnits[$pos / 3] . ' ';
        //         $isAnyMajorUnit = false;
        //     }
        // }
        $result = ""; // trim($result);
        if ($result == "")
            $result = "nol";
        return ($result);
    }

    public function getUserDB()
    {
        return DB_USER;
    }

    public function getPassDB()
    {
        return DB_PASS;
    }

    public function getNameDB()
    {
        return DB_NAME;
    }

    public function getHost()
    {
        return DB_HOST;
    }

    public function getPort()
    {
        return DB_PORT;
    }

    public function getJasperConString()
    {
        return 'jdbc:postgresql://' . $this->getHost() . ':' . $this->getPort() . '/' . $this->getNameDB();
    }

    public function isDataExistReport($sql, &$isexist, &$max)
    {
        $max = "0";
        $isexist = false;
        $results = \Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($results as $result) {
            $max = $result['count'];
        }
        if ($max == "0") {
            $isexist = false;
        } else {
            $isexist = true;
        }
    }

    public function PrintPDF($report, $params, $namafile)
    {
        //	set_time_limit(0);
        //ini_set('max_execution_time', 0);
        //===================================================================================
        //EXPORT TO PDF
        //===================================================================================
        //require(\Yii::getAlias('@anyname') . "/Report/java/Java.inc");
        //        
        //echo str_replace(",","<br/>",java("java.lang.System")->getProperties());exit;
        //          require("http://localhost:8080/JavaBridge/java/Java.inc");
        $jasperReportsLib = $this->getJasperReportLib();
        $handle = @opendir($jasperReportsLib);

        $java_library_path = "";
        while (($new_item = readdir($handle)) !== false) {
            $java_library_path .= 'file:' . $jasperReportsLib . '/' . $new_item . ';';
        }

        $jasperConnectString = $this->getJasperConString();
        java_require($java_library_path);
        $Conn = new Java("org.altic.jasperReports.JdbcConnection");
        $Conn->setDriver("org.postgresql.Driver");
        $Conn->setConnectString($jasperConnectString);
        $Conn->setUser($this->getUserDB());
        $Conn->setPassword($this->getPassDB());

        $fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
        $jasperPrint = $fillManager->fillReport($report, $params, $Conn->getConnection());
        $outputPath = realpath(".") . "/" . ".pdf";
        $exportManager = new JavaClass("net.sf.jasperreports.engine.JasperExportManager");
        $exportManager->exportReportToPdfFile($jasperPrint, $outputPath);

        //        $exportManager->setContentType("application/pdf");
        //        $exportManager->setHeader("Content-disposition", "attachment; filename=" +
        //                        "Example.pdf");
        //$Conn->close();
        // header('Cache-Control: private');
        //  header('Content-Transfer-Encoding: binary');
        //  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        //  header("Content-Type: application/force-download\n");
        //    header("Content-Disposition: attachment; filename=$namafile.pdf");
        //  header("Cache-Control", "no-cache");
        //   header("Pragma", "no-cache");
        //    header('Expires: 0');
        //     readfile($outputPath);
        \Yii::$app->response->sendFile($outputPath, $namafile . ".pdf", ['inline' => false]);
    }

    public function SavePDF($report, $params, $namafile, $path)
    {
        // echo $path;exit;
        //	set_time_limit(0);
        //ini_set('max_execution_time', 0);
        //===================================================================================
        //EXPORT TO PDF
        //===================================================================================
        //require(\Yii::getAlias('@anyname') . "/Report/java/Java.inc");
        //        
        //echo str_replace(",","<br/>",java("java.lang.System")->getProperties());exit;
        //          require("http://localhost:8080/JavaBridge/java/Java.inc");
        $jasperReportsLib = $this->getJasperReportLib();
        $handle = @opendir($jasperReportsLib);

        $java_library_path = "";
        while (($new_item = readdir($handle)) !== false) {
            $java_library_path .= 'file:' . $jasperReportsLib . '/' . $new_item . ';';
        }

        $jasperConnectString = $this->getJasperConString();
        java_require($java_library_path);
        $Conn = new Java("org.altic.jasperReports.JdbcConnection");
        $Conn->setDriver("org.postgresql.Driver");
        $Conn->setConnectString($jasperConnectString);
        $Conn->setUser($this->getUserDB());
        $Conn->setPassword($this->getPassDB());

        $fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
        $jasperPrint = $fillManager->fillReport($report, $params, $Conn->getConnection());
        // $outputPath = realpath(".") . "/" . ".pdf";
        $exportManager = new JavaClass("net.sf.jasperreports.engine.JasperExportManager");

        (chmod(Yii::getAlias('@backend') . '/web/uploads/' . \Yii::$app->enum->getparentid() . '/pdf/', 0777));
        $exportManager->exportReportToPdfFile($jasperPrint, $path);
        chmod($path, 0777);
        //        $exportManager->setContentType("application/pdf");
        //        $exportManager->setHeader("Content-disposition", "attachment; filename=" +
        //                        "Example.pdf");
        //$Conn->close();
        // header('Cache-Control: private');
        //  header('Content-Transfer-Encoding: binary');
        //  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        //  header("Content-Type: application/force-download\n");
        //    header("Content-Disposition: attachment; filename=$namafile.pdf");
        //  header("Cache-Control", "no-cache");
        //   header("Pragma", "no-cache");
        //    header('Expires: 0');
        //     readfile($outputPath);
        // \Yii::$app->response->sendFile($outputPath, $namafile . ".pdf", ['inline' => false]);
    }


    public function PrintExcel($report, $params, $namafile)
    {
        //	set_time_limit(0);
        //ini_set('max_execution_time', 0);
        // require(\Yii::getAlias('@anyname') . "/Report/java/Java.inc");

        $jasperReportsLib = $this->getJasperReportLib();
        $handle = @opendir($jasperReportsLib);

        $java_library_path = "";
        while (($new_item = readdir($handle)) !== false) {
            $java_library_path .= 'file:' . $jasperReportsLib . '/' . $new_item . ';';
        }

        $jasperConnectString = $this->getJasperConString();
        java_require($java_library_path);
        $Conn = new Java("org.altic.jasperReports.JdbcConnection");
        $Conn->setDriver("org.postgresql.Driver");
        $Conn->setConnectString($jasperConnectString);
        $Conn->setUser($this->getUserDB());
        $Conn->setPassword($this->getPassDB());

        $fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
        //===================================================================================
        //EXPORT TO EXCEL
        //===================================================================================
        $jasperPrint = $fillManager->fillReport($report, $params, $Conn->getConnection());
        $outputPath = realpath(".") . "/" . ".xls";
        $exporter = new java("net.sf.jasperreports.engine.export.JRXlsExporter");
        //        $exporter = new java("net.sf.jasperreports.export.engine.export.xls.remove.empty.space.between.columns");
        //        $exporter = new java("net.sf.jasperreports.export.xls.white.page.background");
        //$exporter->setParameter(java("net.sf.jasperreports.engine.export.JRXlsExporterParameter")->IS_IGNORE_GRAPHICS, java("java.lang.Boolean")->FALSE);
        $exporter->setParameter(java("net.sf.jasperreports.engine.export.JRXlsExporterParameter")->IS_DETECT_CELL_TYPE, java("java.lang.Boolean")->TRUE);
        $exporter->setParameter(java("net.sf.jasperreports.engine.export.JRXlsExporterParameter")->IS_REMOVE_EMPTY_SPACE_BETWEEN_COLUMNS, java("java.lang.Boolean")->TRUE);
        $exporter->setParameter(java("net.sf.jasperreports.engine.export.JRXlsExporterParameter")->IS_REMOVE_EMPTY_SPACE_BETWEEN_ROWS, java("java.lang.Boolean")->TRUE);
        $exporter->setParameter(java("net.sf.jasperreports.engine.export.JRXlsExporterParameter")->IS_IGNORE_CELL_BACKGROUND, java("java.lang.Boolean")->TRUE);
        $exporter->setParameter(java("net.sf.jasperreports.engine.export.JRXlsExporterParameter")->IS_WHITE_PAGE_BACKGROUND, java("java.lang.Boolean")->FALSE);
        //        $exporter->setParameter(java("net.sf.jasperreports.engine.export.JRXlsExporterParameter")->ONLY_PRINT_DETAIL_BAND, java("java.lang.Boolean")->TRUE);
        //        $exporter->setParameter(java("net.sf.jasperreports.engine.export.JRXlsExporterParameter")->IS_ONE_PAGE_PER_SHEET, java("java.lang.Boolean")->FALSE);
        $exporter->setParameter(java("net.sf.jasperreports.engine.export.JRXlsExporterParameter")->MAXIMUM_ROWS_PER_SHEET, -1);
        $exporter->setParameter(java("net.sf.jasperreports.engine.JRExporterParameter")->JASPER_PRINT, $jasperPrint);
        $exporter->setParameter(java("net.sf.jasperreports.engine.JRExporterParameter")->OUTPUT_FILE_NAME, $outputPath);

        //$Conn->close();
        //  header('Cache-Control: private');
        //  header("Content-Type: application/force-download\n");
        //  header("Cache-Control", "no-cache");
        //   header("Pragma", "no-cache");
        //  header("Content-Disposition: attachment; filename=$namafile.xls");
        //  header('Expires: 0');
        $exporter->exportReport();
        \Yii::$app->response->sendFile($outputPath, $namafile . ".xls", ['inline' => false]);
        //  readfile($outputPath);
    }

    public function PrintDoc($report, $params, $namafile)
    {
        //        require(\Yii::getAlias('@anyname') . "/Report/java/Java.inc");
        //  require("http://localhost:8080/JavaBridge/java/Java.inc");

        $jasperReportsLib = $this->getJasperReportLib();
        $handle = @opendir($jasperReportsLib);

        $java_library_path = "";
        while (($new_item = readdir($handle)) !== false) {
            $java_library_path .= 'file:' . $jasperReportsLib . '/' . $new_item . ';';
        }

        $jasperConnectString = $this->getJasperConString();
        java_require($java_library_path);
        $Conn = new Java("org.altic.jasperReports.JdbcConnection");
        $Conn->setDriver("org.postgresql.Driver");
        $Conn->setConnectString($jasperConnectString);
        $Conn->setUser($this->getUserDB());
        $Conn->setPassword($this->getPassDB());

        $fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
        //===================================================================================
        //EXPORT TO EXCEL
        //===================================================================================
        $jasperPrint = $fillManager->fillReport($report, $params, $Conn->getConnection());
        $outputPath = realpath(".") . "/" . ".doc";

        $exporter = new java("net.sf.jasperreports.engine.export.ooxml.JRDocxExporter");
        //$exporter->setParameter(java("net.sf.jasperreports.engine.export.JRXlsExporterParameter")->IS_IGNORE_GRAPHICS, java("java.lang.Boolean")->FALSE);
        // $exporter->setParameter(java("net.sf.jasperreports.engine.export.JRXlsExporterParameter")->IS_DETECT_CELL_TYPE, java("java.lang.Boolean")->TRUE);
        // $exporter->setParameter(java("net.sf.jasperreports.engine.export.JRXlsExporterParameter")->IS_REMOVE_EMPTY_SPACE_BETWEEN_COLUMNS, java("java.lang.Boolean")->TRUE);
        $exporter->setParameter(java("net.sf.jasperreports.engine.JRExporterParameter")->JASPER_PRINT, $jasperPrint);
        $exporter->setParameter(java("net.sf.jasperreports.engine.JRExporterParameter")->OUTPUT_FILE_NAME, $outputPath);

        //$Conn->close();
        //  header('Cache-Control: private');
        // header("Content-Type: application/force-download\n");
        //   header("Cache-Control", "no-cache");
        //  header("Pragma", "no-cache");
        //  header("Content-Disposition: attachment; filename=$namafile.doc");
        //   header('Expires: 0');
        $exporter->exportReport();
        \Yii::$app->response->sendFile($outputPath, $namafile . ".doc", ['inline' => false]);
        //    readfile($outputPath);
    }

    public function getJasperReportLib()
    {
        //        $path_java = Yii::app()->initconfig->getConfig('javalocal');
        //return '/usr/lib/jvm/jdk1.7.0_09'; //$path_java;
        return PATH_JAVA;
        // return 'C:\Program Files (x86)\Java\jdk1.7.0_04\jre\lib\ext'; //$path_java;
    }

    function getJarak($latfrom, $lngfrom, $latto, $lngto)
    {

        /* DUMMY	
        $latitudeFrom = 1.1298299093456632; 
        $longitudeFrom = 104.09588083276148; 
        $latitudeTo = 1.128369915756163; 
        $longitudeTo = 104.10153940623157; 
         */

        $latitudeFrom = $latfrom;
        $longitudeFrom = $lngfrom;
        $latitudeTo = $latto;
        $longitudeTo = $lngto;
        $earthRadius = 6371000;

        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }



    public static function cleanparam($param, $opts = [])
    {

        $param = strtolower($param);
        if ($param == '') {
            return false;
        }


        if (str_contains($param, 'script')) {
            return false;
        }
        if (str_contains($param, 'or')) {
            return false;
        }
        if (str_contains($param, 'piter')) {
            return false;
        }



        // opsi default
        $defaults = [
            'trim' => true,
            'maxlen' => 512,        // batasi panjang input
            'alphanumeric' => false // jika true -> hanya izinkan huruf/angka dan beberapa simbol
        ];
        $cfg = array_merge($defaults, $opts);

        if ($param === null)
            return false;
        if (!is_string($param) && !is_numeric($param))
            return false;

        // cast ke string
        $param = (string) $param;

        if ($cfg['trim'])
            $param = trim($param);

        // hapus null bytes (sering dimanfaatkan)
        $param = str_replace("\0", '', $param);

        // hapus tag HTML (untuk XSS)
        $param = strip_tags($param);

        // hilangkan karakter kontrol (except newline/tab jika mau)
        $param = preg_replace('/[\x00-\x1F\x7F]/u', '', $param);

        // opsional: batasi karakter ke alphanumeric & beberapa simbol
        if ($cfg['alphanumeric']) {
            // izinkan huruf, angka, spasi, dash, underscore, titik, @ dan :
            $param = preg_replace('/[^A-Za-z0-9 \-\_\.\@\:\,]/u', '', $param);
        }

        // potong panjang jika melebihi maxlen
        if ($cfg['maxlen'] !== null && mb_strlen($param) > $cfg['maxlen']) {
            $param = mb_substr($param, 0, (int) $cfg['maxlen']);
        }

        // escape untuk output HTML (gunakan ini saat menampilkan di view)
        // NOTE: jangan pakai htmlspecialchars di sini jika Anda akan mem-bind ke DB.
        // return sanitized string untuk dipakai di DB (binding) atau view setelah htmlspecialchars.
        return $param === '' ? false : $param;
    }
}

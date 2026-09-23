<?php

namespace common\components;

use yii\helpers\Url;
use Yii;

class LangClass extends \yii\base\Component
{
  public function init()
  {

    date_default_timezone_set("Asia/Jakarta");


    parent::init();
  }


  public function setLang($lang)
  {
    // $cookies = Yii::$app->response->cookies;

    // $cookies->add(new Cookie([
    //     'language' => $lang,
    // ]));

    $session = Yii::$app->session;
    $session->set('language', $lang);
    $userid = Yii::$app->user->id;
    if ($userid) {
      Yii::$app->db->createCommand("UPDATE users SET lang = :lang WHERE userid = :id")
        ->bindValue(':lang', $lang)
        ->bindValue(':id', $userid)
        ->execute();
    }
    // $_SESSION['language'] = $lang;
    Yii::$app->language = $lang;
  }
  public function getLang()
  {
    // $cookies = Yii::$app->request->cookies->get('language');
    //$language = "en";
    // $cookies = Yii::$app->request->cookies;
    // if ($cookies->has('languange'))
    //     $language = $cookies->getValue('languange');
    // $userid = Yii::$app->user->id;
    // get a session variable. The following usages are equivalent:
    //$language = Yii::$app->session->get('language');

    // $language = !empty(Yii::$app->session->get('language')) ? Yii::$app->session->get('language') : 'en';
    // return $language;
    $session = Yii::$app->session;
    $language = $session->get('language');

    if (empty($language)) {
      // Ambil dari database jika user sudah login
      $userid = Yii::$app->user->id;
      if ($userid) {
        $language = Yii::$app->db->createCommand("SELECT lang FROM users WHERE userid = :id")
          ->bindValue(':id', $userid)
          ->queryScalar();
      }
    }

    // Jika tetap kosong, set default ke 'en'
    return !empty($language) ? $language : 'id';
  }

  function genLang()
  {
    $path = Yii::getAlias('@common') . '/json/';
    $sql = "SELECT A.lang_type||'.'||lang_text as id,lang_en,lang_id FROM langs A";
    $rows = Yii::$app->db->createCommand($sql)->queryAll();
    $json = json_encode($rows);
    $filename = "lang.json";
    $filepath = $path . $filename;
    $fp = fopen($filepath, 'w+');
    fwrite($fp, $json);
    fclose($fp);
    var_dump($json);
    exit;
  }

  public function t($type, $param)
  {
    $lang = Yii::$app->language;
    $id = $type . "." . $param . "";
    $result = $id;

    if (true) {
      $path_lang = Yii::getAlias('@common') . '/json/lang.json';
      if (!file_exists($path_lang)) {
        return $result;
      }

      $db_lang = file_get_contents($path_lang);

      $json = json_decode($db_lang);

      foreach ($json as $item) {
        if ($item->id == $id) {
          if ($lang == "id") {
            $result = $item->langtext_id;
          } else {
            $result = $item->langtext_en;
          }
        }
      }
    } else {
      $sql = "select lang_" . $lang . " as result from langs where lang_type='" . $type . "' and lang_text='" . $param . "' limit 1";

      //echo $sql;exit;
      $rows = Yii::$app->db->createCommand($sql)->queryAll();
      $result = $param;
      foreach ($rows as $row) {
        $result = $row['result'];
      }
    }
    return $result;
  }
}

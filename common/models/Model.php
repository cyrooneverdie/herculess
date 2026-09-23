<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Helper model untuk membuat / memproses multiple models dari post data
 */
class Model extends \yii\base\Model
{
    /**
     * Membuat beberapa model dari post data dengan ID custom
     * Contoh: Usermenu -> usermenuid
     *
     * @param string $modelClass Nama class model
     * @param array $multipleModels Array model existing
     * @param string $id Nama atribut ID primary
     * @return array Array model siap dipakai
     */
    public static function createMultipleID($modelClass, $multipleModels = [], $id)
    {
        $model = new $modelClass;
        $formName = $model->formName();
        
        $post = Yii::$app->request->post($formName);
        $models = [];

        // mapping existing models by $id
        if (!empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, $id, $id));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                // kalau ID ada dan cocok dengan existing model
                if (isset($item[$id]) && $item[$id] !== '' && isset($multipleModels[$item[$id]])) {
                    $models[] = $multipleModels[$item[$id]];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }

        return $models;
    }

    /**
     * Load multiple post data ke array model
     *
     * @param array $models
     * @param array $data
     * @return bool
     */
    public static function loadMultiple($models, $data, $formName = null)
    {
        $success = true;
        foreach ($models as $i => $model) {
            $scope = $formName === null ? $model->formName() : $formName;
            if (isset($data[$scope][$i]) && is_array($data[$scope][$i])) {
                if (!$model->load($data[$scope][$i], '')) {
                    $success = false;
                }
            }
        }
        return $success;
    }

    /**
     * Validasi multiple model
     *
     * @param array $models
     * @return bool
     */
public static function validateMultiple($models, $attributeNames = null)
{
    $valid = true;
    foreach ($models as $model) {
        if (!$model->validate($attributeNames)) {
            $valid = false;
        }
    }
    return $valid;
}

}

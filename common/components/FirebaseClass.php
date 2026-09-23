<?php
namespace common\components;

use Kreait\Firebase\Factory;
use yii\base\Component;
use Yii;

class FirebaseClass extends Component
{
    public $serviceAccount;
    private $_firestore;

    public function init()
    {
        parent::init();
        $factory = (new Factory)
            ->withServiceAccount(Yii::getAlias($this->serviceAccount));
        $this->_firestore = $factory->createFirestore()->database();
    }

    public function getFirestore()
    {
        return $this->_firestore;
    }
}

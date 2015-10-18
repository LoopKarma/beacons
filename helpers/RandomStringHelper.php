<?php
namespace app\helpers;

use yii\base\Object;

class RandomStringHelper extends Object
{
    public $alphabet = '0123456789bcdefghkmnpqrstuvwxyz';

    public function generateString($length = 10)
    {
        $characters = $this->alphabet;
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}

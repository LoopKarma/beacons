<?php

namespace app\models;

use Yii;
use yii\base\InvalidParamException;
use yii\behaviors\TimestampBehavior;
use yii\validators\DateValidator;

/**
 * This is the model class for table "ai_coupon".
 *
 * @property integer $coupon_id
 * @property string $create_date
 * @property string $update_date
 * @property integer $template_id
 * @property integer $merchant_id
 * @property integer $pos_id
 * @property string $client
 * @property integer $confirmed
 * @property integer $message
 * @property string $uuid
 * @property string $major
 * @property string $minor
 * @property string $serial_number
 */
class Coupon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_id', 'merchant_id', 'pos_id', 'client', 'message', 'uuid', 'major', 'minor'], 'required'],
            [['template_id', 'merchant_id', 'pos_id', 'confirmed', 'message'], 'integer'],
            [['client', 'serial_number'], 'string', 'max' => 100],
            [['uuid', 'major', 'minor'], 'string', 'max' => 32],
            [['serial_number'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coupon_id' => 'ID',
            'create_date' => 'Дата генерации',
            'template_id' => 'ID шаблона',
            'merchant_id' => 'Мерчант',
            'pos_id' => 'Точка продаж',
            'client' => 'Client',
            'confirmed' => 'Подтверждено',
            'message' => 'Сообщение',
            'uuid' => 'Uuid',
            'major' => '* Major *',
            'minor' => '* Minor *',
            'serial_number' => 'Серийный номер',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_date',
                'updatedAtAttribute' => false,
                'value' => function () {
                    return date("Y-m-d H:i:s");
                },
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(Merchant::className(), ['merchant_id' => 'merchant_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(CouponTemplate::className(), ['template_id' => 'template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPos()
    {
        return $this->hasOne(Pos::className(), ['pos_id' => 'pos_id']);
    }

    public function getCouponCount($fromDate, $toDate = false, $merchant = false)
    {
        $validator = new DateValidator(['format' => 'Y-m-d']);
        if (!$validator->validate($fromDate) || ($toDate && !$validator->validate($fromDate))) {
            throw new InvalidParamException('Invalid date format');
        } else {
            $query = $this->find()->where(['>=', 'create_date', $fromDate]);
            if ($toDate) {
                $query->andWhere(['<=', 'create_date', $toDate]);
            }
            if ($merchant) {
                $query->andWhere(['merchant_id' => $merchant]);
            }
            return $query->count();
        }
    }

    public function getAttributeValueAmongAll($attribute, $action, $merchant = false)
    {
        if (in_array($attribute, $this->attributes())) {
            if (in_array($action, ['min', 'max'])) {
                $query = $this
                    ->find()
                    ->select([$attribute, 'COUNT(*) as cnt'])
                    ->groupBy($attribute)
                    ->orderBy(['cnt' => $action == 'max' ? SORT_DESC : SORT_ASC]);
                if ($merchant) {
                    $query->where(['merchant_id' => $merchant]);
                }
                $res = $query
                    ->limit(1)
                    ->asArray()
                    ->one();

                return $res[$attribute];
            } else {
                throw new InvalidParamException('Action is not exist');
            }
        } else {
            throw new InvalidParamException('Attribute ' . $attribute .' is not exist');
        }
    }
}

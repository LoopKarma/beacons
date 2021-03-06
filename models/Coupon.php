<?php

namespace app\models;

use Yii;
use app\helpers\CsvWriter;
use app\helpers\RandomStringHelper;
use yii\base\InvalidParamException;
use yii\behaviors\TimestampBehavior;
use yii\validators\DateValidator;

/**
 * This is the model class for table "ai_coupon".
 *
 * @property integer $coupon_id
 * @property string $create_date
 * @property string $change_date
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
    const SERIAL_NUMBER_LENGTH = 8;
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
            [['serial_number'], 'string', 'max' => static::SERIAL_NUMBER_LENGTH],
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
            'create_date' => 'Дата показа',
            'change_date' => 'Дата изменения',
            'template_id' => 'ID шаблона',
            'merchant_id' => 'Мерчант',
            'pos_id' => 'Точка продаж',
            'client' => 'Client',
            'confirmed' => 'Погашен',
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
                'updatedAtAttribute' => 'change_date',
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

    public static function generateSerialNumber($merchantId)
    {
        $lastCoupon = Coupon::find()
            ->select('coupon_id')
            ->where(['merchant_id' => $merchantId])
            ->orderBy(['coupon_id' => SORT_DESC])
            ->asArray()
            ->one();
        $nextCouponId = $lastCoupon['coupon_id'] + 1;

        $randomString = new RandomStringHelper(['alphabet' => '0123456789abc']);
        return $randomString->generateString(static::SERIAL_NUMBER_LENGTH - strlen($nextCouponId)).$nextCouponId;

    }

    public function getCouponCount($fromDate, $toDate = false, $merchant = false)
    {
        $validator = new DateValidator(['format' => 'php:Y-m-d']);
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

    public function getConfirmedCoupons($fromDate, $toDate = false, $merchant = false)
    {
        $validator = new DateValidator(['format' => 'php:Y-m-d']);
        if (!$validator->validate($fromDate) || ($toDate && !$validator->validate($fromDate))) {
            throw new InvalidParamException('Invalid date format');
        } else {
            $query = $this->find()->where(['>=', 'change_date', $fromDate])->andWhere(['confirmed' => 1]);
            if ($toDate) {
                $query->andWhere(['<=', 'change_date', $toDate]);
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

    /**
     * @param \yii\db\QueryInterface $searchQuery
     * @return \app\helpers\CsvWriter|bool
     */
    public function generateCsv($searchQuery)
    {
        $fieldset = $this->attributeLabels();
        /** @var array $coupons */
        $coupons = $searchQuery->asArray()->all();
        if (!empty($coupons)) {
            $writer = new CsvWriter();
            $writer->writeHeader($fieldset);
            foreach ($coupons as $row) {
                $line = [];
                foreach ($fieldset as $key => $field) {
                    $line[] = $row[$key];
                }
                $writer->writeLine($line);
            }
        }
        return isset($writer) ? $writer : false;
    }

    public function getCsvFields()
    {
        return [
            'coupon_id' => 'ID',
            'create_date' => 'Дата показа',
            'change_date' => 'Дата изменения',
            'template_id' => 'ID шаблона',
            'merchant_id' => 'Мерчант',
            'pos_id' => 'Точка продаж',
            'client' => 'Client',
            'confirmed' => 'Погашен',
            'message' => 'Сообщение',
            'uuid' => 'Uuid',
            'major' => '* Major *',
            'minor' => '* Minor *',
            'serial_number' => 'Серийный номер',
        ];
    }
}

<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\helpers\RandomStringHelper;

/**
 * This is the model class for table "{{%barcode_message}}".
 *
 * @property integer $message_id
 * @property string $create_date
 * @property string $update_date
 * @property integer $merchant_id
 * @property string $message
 * @property integer $utilize
 */
class BarcodeMessage extends \yii\db\ActiveRecord
{
    const RANDOM_MESSAGE_LENGTH = 8;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%barcode_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['merchant_id'], 'required'],
            [['merchant_id', 'utilize'], 'integer'],
            [['message'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_id' => 'ID Сообщения',
            'create_date' => 'Дата создания',
            'update_date' => 'Дата изменения',
            'merchant_id' => 'Мерчант ID',
            'message' => 'Текст сообщения',
            'utilize' => 'Использовано',
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
                'updatedAtAttribute' => 'update_date',
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
     * @param $merchantId
     * @return string
     */
    public static function generateMessage($merchantId)
    {
        $lastCoupon = Coupon::find()
            ->select('coupon_id')
            ->where(['merchant_id' => $merchantId])
            ->orderBy(['coupon_id' => SORT_DESC])
            ->asArray()
            ->one();

        $nextCouponId = $lastCoupon['coupon_id'] + 1;

        $randomString = new RandomStringHelper;
        return $randomString
            ->generateString(BarcodeMessage::RANDOM_MESSAGE_LENGTH - strlen($nextCouponId)).$nextCouponId;
    }


    /**
     * @param CouponTemplate $template
     * @return bool
     */
    public static function sendNoAvaliableMessagesInfo(\app\models\CouponTemplate $template)
    {
        $text = '<p>Закончились доступные сообщения баркода у шаблона ';
        $text .= '<a href="'.Yii::$app->params['siteUrl'].'/template/view?id='.$template->template_id.'">'
            .($template->name ?: $template->template_id).'</a>';

        return Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['fromEmail'])
            ->setTo(Yii::$app->params['sendEmail'])
            ->setSubject('Кончились загруженные сообщения для шаблона.')
            ->setHtmlBody($text)
            ->send();
    }
}

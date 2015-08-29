<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ai_template_pos".
 *
 * @property integer $template_id
 * @property integer $pos_id
 *
 * @property Pos $pos
 * @property CouponTemplate $template
 */
class TemplatePos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%template_pos}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_id', 'pos_id'], 'required'],
            [['template_id', 'pos_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'template_id' => 'Template ID',
            'pos_id' => 'Pos ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPos()
    {
        return $this->hasOne(Pos::className(), ['pos_id' => 'pos_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(CouponTemplate::className(), ['template_id' => 'template_id']);
    }
}

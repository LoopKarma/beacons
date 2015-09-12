<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CouponTemplate;

/**
 * TemplateSearch represents the model behind the search form about `app\models\CouponTemplate`.
 */
class TemplateSearch extends CouponTemplate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_id', 'active', 'merchant_id', 'without_barcode'], 'integer'],
            [['create_date', 'update_date', 'coupon', 'background_color', 'foreground_color', 'organization_name', 'team_identifier', 'logo_text', 'description', 'beacon_relevant_text', 'barcode_format', 'barcode_message_encoding', 'icon', 'icon_retina', 'logo', 'logo_retina', 'strip_image', 'strip_image_retina'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = CouponTemplate::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'template_id' => $this->template_id,
            'create_date' => $this->create_date,
            'update_date' => $this->update_date,
            'active' => $this->active,
            'merchant_id' => $this->merchant_id,
            'without_barcode' => $this->without_barcode,
        ]);

        $query->andFilterWhere(['like', 'coupon', $this->coupon])
            ->andFilterWhere(['like', 'background_color', $this->background_color])
            ->andFilterWhere(['like', 'foreground_color', $this->foreground_color])
            ->andFilterWhere(['like', 'organization_name', $this->organization_name])
            ->andFilterWhere(['like', 'team_identifier', $this->team_identifier])
            ->andFilterWhere(['like', 'logo_text', $this->logo_text])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'beacon_relevant_text', $this->beacon_relevant_text])
            ->andFilterWhere(['like', 'barcode_format', $this->barcode_format])
            ->andFilterWhere(['like', 'barcode_message_encoding', $this->barcode_message_encoding])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'icon_retina', $this->icon_retina])
            ->andFilterWhere(['like', 'logo', $this->logo])
            ->andFilterWhere(['like', 'logo_retina', $this->logo_retina])
            ->andFilterWhere(['like', 'strip_image', $this->strip_image])
            ->andFilterWhere(['like', 'strip_image_retina', $this->strip_image_retina]);

        return $dataProvider;
    }
}

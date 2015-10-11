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
            [
                ['template_id', 'active', 'merchant_id', 'without_barcode', 'send_unlimited'],
                'integer'
            ],
            [
                [
                    'name',
                    'create_date',
                    'update_date',
                    'coupon',
                    'background_color',
                    'foreground_color',
                    'organization_name',
                    'team_identifier',
                    'logo_text',
                    'description',
                    'beacon_relevant_text',
                    'barcode_format',
                    'barcode_message_encoding',
                    'icon',
                    'icon2x',
                    'icon3x',
                    'logo',
                    'logo2x',
                    'logo3x',
                    'strip',
                    'strip2x',
                    'strip3x',
                ],
                'safe'
            ],
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
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'template_id' => $this->template_id,
            'update_date' => $this->update_date,
            'active' => $this->active,
            'merchant_id' => $this->merchant_id,
            'without_barcode' => $this->without_barcode,
            'send_unlimited' => $this->send_unlimited,
            'icon' => $this->icon,
            'icon2x' => $this->icon2x,
            'icon3x' => $this->icon3x,
            'logo' => $this->logo,
            'logo2x' => $this->logo2x,
            'logo3x' => $this->logo3x,
            'strip' => $this->strip,
            'strip2x' => $this->strip2x,
            'strip3x' => $this->strip3x,
        ]);

        $query->andFilterWhere(['like', 'coupon', $this->coupon])
            ->andFilterWhere(['like', 'background_color', $this->background_color])
            ->andFilterWhere(['like', 'create_date', $this->create_date])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'foreground_color', $this->foreground_color])
            ->andFilterWhere(['like', 'organization_name', $this->organization_name])
            ->andFilterWhere(['like', 'team_identifier', $this->team_identifier])
            ->andFilterWhere(['like', 'logo_text', $this->logo_text])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'beacon_relevant_text', $this->beacon_relevant_text])
            ->andFilterWhere(['like', 'barcode_format', $this->barcode_format])
            ->andFilterWhere(['like', 'barcode_message_encoding', $this->barcode_message_encoding]);

        return $dataProvider;
    }
}

<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Coupon;

/**
 * CouponSearch represents the model behind the search form about `app\models\Coupon`.
 */
class CouponSearch extends Coupon
{
    public $address;
    public $merchant_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_id', 'template_id', 'merchant_id', 'pos_id', 'confirmed'], 'integer'],
            [['create_date', 'client', 'message', 'uuid', 'major', 'minor', 'serial_number', 'address'], 'safe'],
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
        $query = Coupon::find();

        $query->joinWith(['pos']);
        if ($this->merchant_id) {
            $query->where(['{{%coupon}}.merchant_id' => $this->merchant_id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);


        $dataProvider->sort->attributes['address'] = [
            'asc' => ['{{%pos}}.address' => SORT_ASC],
            'desc' => ['{{%pos}}.address' => SORT_DESC],
        ];

        if ($this->load($params) && !$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'coupon_id' => $this->coupon_id,
            'template_id' => $this->template_id,
            '{{%coupon}}.merchant_id' => $this->merchant_id,
            '{{%coupon}}.pos_id' => $this->pos_id,
            'confirmed' => $this->confirmed,
        ]);

        $query->andFilterWhere(['like', 'client', $this->client])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', '{{%coupon}}.create_date', $this->create_date])
            ->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', 'major', $this->major])
            ->andFilterWhere(['like', '{{%coupon}}.minor', $this->minor])
            ->andFilterWhere(['like', '{{%pos}}.address', $this->address])
            ->andFilterWhere(['like', 'serial_number', $this->serial_number]);


        return $dataProvider;
    }
}

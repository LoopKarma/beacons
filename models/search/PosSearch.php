<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pos;

/**
 * PosSearch represents the model behind the search form about `app\models\Pos`.
 */
class PosSearch extends Pos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pos_id', 'merchant_id'], 'integer'],
            [['create_date', 'update_date', 'address', 'beacon_identifier', 'minor'], 'safe'],
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
        $query = Pos::find();

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
            'pos_id' => $this->pos_id,
            'merchant_id' => $this->merchant_id,
            'create_date' => $this->create_date,
            'update_date' => $this->update_date,
        ]);

        $query->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'beacon_identifier', $this->beacon_identifier])
            ->andFilterWhere(['like', 'minor', $this->minor]);

        return $dataProvider;
    }
}

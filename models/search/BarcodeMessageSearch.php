<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BarcodeMessage;

/**
 * BarcodeMessageSearch represents the model behind the search form about `app\models\BarcodeMessage`.
 */
class BarcodeMessageSearch extends BarcodeMessage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message_id', 'merchant_id', 'utilize'], 'integer'],
            [['create_date', 'update_date', 'message'], 'safe'],
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
        $query = BarcodeMessage::find();

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
            'message_id' => $this->message_id,
            'create_date' => $this->create_date,
            'update_date' => $this->update_date,
            'merchant_id' => $this->merchant_id,
            'utilize' => $this->utilize,
        ]);

        $query->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}

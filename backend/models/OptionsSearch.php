<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Options;

/**
 * OptionsSearch represents the model behind the search form about `backend\models\Options`.
 */
class OptionsSearch extends Options
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'days_payd', 'chat_time'], 'integer'],
            [['model_price', 'elayner_price', 'attachment_price', 'checkpoint_price', 'reteiner_price'], 'number'],
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
        $query = Options::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'model_price' => $this->model_price,
            'elayner_price' => $this->elayner_price,
            'attachment_price' => $this->attachment_price,
            'checkpoint_price' => $this->checkpoint_price,
            'reteiner_price' => $this->reteiner_price,
            'days_payd' => $this->days_payd,
            'chat_time' => $this->chat_time,
        ]);

        return $dataProvider;
    }
}

<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Alerts;

/**
 * AlertsSearch represents the model behind the search form about `backend\models\Alerts`.
 */
class AlertsSearch extends Alerts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'doctor_id_to', 'doctor_id_from', 'date', 'read_status','type'], 'integer'],
            [['text'], 'safe'],
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
        $query = Alerts::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {           return $dataProvider;       }

        $query->andFilterWhere([
            'id' => $this->id,
            'doctor_id_to' => Yii::$app->user->id,
            'doctor_id_from' => $this->doctor_id_from,
            'date' => $this->date,
            'read_status' => $this->read_status,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text]);
        $query->orderBy('read_status ASC, date DESC');

        return $dataProvider;
    }

    public function search0($params)
    {
        $query = Alerts::find();

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
            'doctor_id_to' => Yii::$app->user->id,
            'doctor_id_from' => $this->doctor_id_from,
            'date' => $this->date,
            'read_status' => $this->read_status,
        ]);


        $query->andFilterWhere(['like', 'text', $this->text]);
        //$query->orderBy('date DESC, read_status ASC');

        return $dataProvider;
    }
}

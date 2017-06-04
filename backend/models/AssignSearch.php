<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Assign;

/**
 * AssignSearch represents the model behind the search form about `backend\models\Assign`.
 */
class AssignSearch extends Assign
{
    /** 
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'num', 'pacient_id', 'status', 'level_1_doctor_id', 'level_2_doctor_id', 'level_3_doctor_id', 'level_4_doctor_id', 'level_5_doctor_id', 'level_1_status', 'level_2_status', 'level_3_status', 'level_4_status', 'level_5_status','level'], 'integer'],
            [['level_1_result', 'level_2_result', 'level_3_result', 'level_4_result', 'level_5_result', 'level_1_date', 'level_2_date', 'level_3_date', 'level_4_date', 'level_5_date'], 'safe'],
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
        $query = Assign::find();

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
            'num' => $this->num,
            'pacient_id' => $this->pacient_id,
            'status' => $this->status,
            'level' => $this->level,
            'level_1_doctor_id' => $this->level_1_doctor_id,
            'level_2_doctor_id' => $this->level_2_doctor_id,
            'level_3_doctor_id' => $this->level_3_doctor_id,
            'level_4_doctor_id' => $this->level_4_doctor_id,
            'level_5_doctor_id' => $this->level_5_doctor_id,
            'level_1_status' => $this->level_1_status,
            'level_2_status' => $this->level_2_status,
            'level_3_status' => $this->level_3_status,
            'level_4_status' => $this->level_4_status,
            'level_5_status' => $this->level_5_status,
            'level_1_date' => $this->level_1_date,
            'level_2_date' => $this->level_2_date,
            'level_3_date' => $this->level_3_date,
            'level_4_date' => $this->level_4_date,
            'level_5_date' => $this->level_5_date,
        ]);

        $query->andFilterWhere(['like', 'level_1_result', $this->level_1_result])
            ->andFilterWhere(['like', 'level_2_result', $this->level_2_result])
            ->andFilterWhere(['like', 'level_3_result', $this->level_3_result])
            ->andFilterWhere(['like', 'level_4_result', $this->level_4_result])
            ->andFilterWhere(['like', 'level_5_result', $this->level_5_result]);

        return $dataProvider;
    }
}

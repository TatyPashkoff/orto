<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Reports;

/**
 * ReportsSearch represents the model behind the search form about `backend\models\Reports`.
 */
class ReportsSearch extends Reports
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'num', 'doctor_id', 'pacient_code', 'report_status', 'status', 'type', 'count_models', 'count_elayners', 'count_attachment', 'count_checkpoint', 'count_reteiners'], 'integer'],
            [['date', 'comments'], 'safe'],
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
        $query = Reports::find();

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
            'date' => $this->date,
            'num' => $this->num,
            'doctor_id' => $this->doctor_id,
            'pacient_code' => $this->pacient_code,
            'report_status' => $this->report_status,
            'status' => $this->status,
            'type' => $this->type,
            'count_models' => $this->count_models,
            'count_elayners' => $this->count_elayners,
            'count_attachment' => $this->count_attachment,
            'count_checkpoint' => $this->count_checkpoint,
            'count_reteiners' => $this->count_reteiners,
        ]);

        $query->andFilterWhere(['like', 'comments', $this->comments]);

        return $dataProvider;
    }
}

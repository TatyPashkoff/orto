<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Clinics;

/**
 * ClinicsSearch represents the model behind the search form about `backend\models\Clinics`.
 */
class ClinicsSearch extends Clinics
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'adress', 'phone', 'contacts_admin', 'email'], 'safe'],
            [['model_price', 'elayner_price', 'attachment_price', 'checkpoint_price', 'reteiner_price', 'model_discount', 'elayner_discount', 'attachment_discount', 'checkpoint_discount', 'reteiner_discount'], 'number'],
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
        $query = Clinics::find();

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
            'model_discount' => $this->model_discount,
            'elayner_discount' => $this->elayner_discount,
            'attachment_discount' => $this->attachment_discount,
            'checkpoint_discount' => $this->checkpoint_discount,
            'reteiner_discount' => $this->reteiner_discount,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'adress', $this->adress])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'contacts_admin', $this->contacts_admin])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}

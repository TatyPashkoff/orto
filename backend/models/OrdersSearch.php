<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Orders;

/**
 * OrdersSearch represents the model behind the search form about `backend\models\Orders`.
 */
class OrdersSearch extends Orders
{

    var $version;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'num', 'group_id', 'doctor_id', 'clinics_id', 'pacient_code', 'date_paid', 'type_paid', 'status_paid', 'status_object', 'order_status', 'admin_check', 'status_agreement', 'status', 'scull_type', 'type', 'count_models', 'count_elayners', 'count_attachment', 'count_checkpoint', 'count_reteiners', 'count_models_vp', 'count_elayners_vp', 'count_attachment_vp', 'count_checkpoint_vp', 'count_reteiners_vp', 'count_models_vc', 'count_elayners_vc', 'count_attachment_vc', 'count_checkpoint_vc', 'count_reteiners_vc', 'count_models_nc', 'count_elayners_nc', 'count_attachment_nc', 'count_checkpoint_nc', 'count_reteiners_nc', 'level_1_doctor_id', 'level_2_doctor_id', 'level_3_doctor_id', 'level_4_doctor_id', 'level_5_doctor_id', 'level_1_status', 'level_2_status', 'level_3_status', 'level_4_status', 'level_5_status'], 'integer'],
            [['date', 'level_1_result', 'level_2_result', 'level_3_result', 'level_4_result', 'level_5_result', 'comments', 'files'], 'safe'],
            [['price', 'discount', 'sum_paid','tarif_plan','price_by_plan'], 'number'],
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
        $query = Orders::find();

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
            'group_id' => $this->group_id,
            'doctor_id' => $this->doctor_id,
            'clinics_id' => $this->clinics_id,
            'pacient_code' => $this->pacient_code,
            'date_paid' => $this->date_paid,
            'type_paid' => $this->type_paid,
            'status_paid' => $this->status_paid,
            'status_object' => $this->status_object,
            'order_status' => $this->order_status,
            'admin_check' => $this->admin_check,
            'status_agreement' => $this->status_agreement,
            'status' => $this->status,
            'scull_type' => $this->scull_type,
            'type' => $this->type,
            'price' => $this->price,
            'discount' => $this->discount,
            'sum_paid' => $this->sum_paid,
            'count_models' => $this->count_models,
            'count_elayners' => $this->count_elayners,
            'count_attachment' => $this->count_attachment,
            'count_checkpoint' => $this->count_checkpoint,
            'count_reteiners' => $this->count_reteiners,
            'count_models_vp' => $this->count_models_vp,
            'count_elayners_vp' => $this->count_elayners_vp,
            'count_attachment_vp' => $this->count_attachment_vp,
            'count_checkpoint_vp' => $this->count_checkpoint_vp,
            'count_reteiners_vp' => $this->count_reteiners_vp,
            'count_models_vc' => $this->count_models_vc,
            'count_elayners_vc' => $this->count_elayners_vc,
            'count_attachment_vc' => $this->count_attachment_vc,
            'count_checkpoint_vc' => $this->count_checkpoint_vc,
            'count_reteiners_vc' => $this->count_reteiners_vc,
            'count_models_nc' => $this->count_models_nc,
            'count_elayners_nc' => $this->count_elayners_nc,
            'count_attachment_nc' => $this->count_attachment_nc,
            'count_checkpoint_nc' => $this->count_checkpoint_nc,
            'count_reteiners_nc' => $this->count_reteiners_nc,
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
            'tarif_plan' => $this->tarif_plan,
            'price_by_plan' => $this->price_by_plan,
        ]);

        $query->andFilterWhere(['like', 'level_1_result', $this->level_1_result])
            ->andFilterWhere(['like', 'level_2_result', $this->level_2_result])
            ->andFilterWhere(['like', 'level_3_result', $this->level_3_result])
            ->andFilterWhere(['like', 'level_4_result', $this->level_4_result])
            ->andFilterWhere(['like', 'level_5_result', $this->level_5_result])
            ->andFilterWhere(['like', 'comments', $this->comments])
            ->andFilterWhere(['like', 'files', $this->files]);

        //$query->orderBy('date DESC');
        return $dataProvider;
    }


   





}

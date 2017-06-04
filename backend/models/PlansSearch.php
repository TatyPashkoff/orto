<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Plans;

/**
 * PlansSearch represents the model behind the search form about `backend\models\Plans`.
 */
class PlansSearch extends Plans
{
    public $doctorName;
    public $pacientName;
    public $pacient;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ready', 'count_elayners_vc', 'count_attachment_vc', 'count_checkpoint_vc', 'count_reteiners_vc', 'count_elayners_nc', 'count_attachment_nc', 'count_checkpoint_nc', 'count_reteiners_nc', 'doctor_id', 'pacient_id', 'order_id', 'level_1_doctor_id', 'level_2_doctor_id', 'level_3_doctor_id', 'level_4_doctor_id', 'level_5_doctor_id', 'level_1_status', 'level_2_status', 'level_3_status', 'level_4_status', 'level_5_status'], 'integer'],
            [['version', 'doctorName', 'pacientName', 'ver_confirm', 'correct', 'approved', 'level_1_result', 'level_2_result', 'level_3_result', 'level_4_result', 'level_5_result', 'comments', 'files'], 'safe'],
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
        $query = Plans::find();

        // add conditions that should always apply here
        $query->with('pacient');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,


        ]);





        $dataProvider->setSort([
            'attributes' => [
                'pacient' => [
                    'asc' =>    [  'orto_pacients.name' => SORT_ASC ],
                    'desc' =>   [   'orto_pacients.name' => SORT_DESC ],
                    'label' => 'name'
                ],
            ],
            'defaultOrder' => ['pacient' => SORT_ASC],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $user_id = Yii::$app->user->id;
        $role = Yii::$app->user->identity->role;
        if ($role == 1) {
            $doctor_id = $user_id;
        } else {
            $doctor_id = $this->doctor_id;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'ready' => $this->ready,
            //'count_models' => $this->count_models,
            'count_elayners_nc' => $this->count_elayners_nc,
            'count_attachment_nc' => $this->count_attachment_nc,
            'count_checkpoint_nc' => $this->count_checkpoint_nc,
            'count_reteiners_nc' => $this->count_reteiners_nc,
            'count_elayners_vc' => $this->count_elayners_vc,
            'count_attachment_vc' => $this->count_attachment_vc,
            'count_checkpoint_vc' => $this->count_checkpoint_vc,
            'count_reteiners_vc' => $this->count_reteiners_vc,
            'doctor_id' => $this->doctor_id,
            'pacient_id' => $this->pacient_id,
            'order_id' => $this->order_id,
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
        ]);

        $query->joinWith(['doctor']);



        $query->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'ver_confirm', $this->ver_confirm])
            ->andFilterWhere(['like', 'correct', $this->correct])
            ->andFilterWhere(['like', 'approved', $this->approved])
            ->andFilterWhere(['like', 'level_1_result', $this->level_1_result])
            ->andFilterWhere(['like', 'level_2_result', $this->level_2_result])
            ->andFilterWhere(['like', 'level_3_result', $this->level_3_result])
            ->andFilterWhere(['like', 'level_4_result', $this->level_4_result])
            ->andFilterWhere(['like', 'level_5_result', $this->level_5_result])
            ->andFilterWhere(['like', 'comments', $this->comments])
            ->andFilterWhere(['like', 'files', $this->files])
            ->andFilterWhere(['like', 'fullname', $this->doctorName])
            ->andFilterWhere(['like', 'name', $this->pacientName]);
        return $dataProvider;

    }
//    public function searchDoctor($params)
//    {
//        $query = Plans::find();
//
//        // add conditions that should always apply here
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);
//
//        $this->load($params);
//
//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }
//        $user_id = Yii::$app->user->id;
//        $role = Yii::$app->user->identity->role;
//        if ($role == 1) {
//            $doctor_id = $user_id;
//        } else {
//            $doctor_id = $this->doctor_id;
//        }
//
//        // grid filtering conditions
//
//        $query->joinWith(['doctor']);
//
//
//        $query->andFilterWhere(['like', 'fullname', $this->doctorName])
//        return $dataProvider;
//    }
}

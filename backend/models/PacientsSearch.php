<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use backend\models\Pacients;
use backend\models\Assign;

/**
 * PacientsSearch represents the model behind the search form about `backend\models\Pacients`.
 */
class PacientsSearch extends Pacients
{

    public $paid; // оплачено за вп

    //public $user;
    public $paket; // пакет
    public $vp_paid; // оплачено за вп
    public $debt; // задолженность

    public $fullName;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'doctor_id', 'order_id', 'clinic_id', 'vp_id', 'code', 'age', 'date', 
                'gender', 'status', 'alert_date', 'type_paid', 'var_paid','product_id','scull_top','scull_bottom','date_paid'], 'integer'],
            [['alert_msg', 'name', 'phone', 'diagnosis', 'result', 'files','fullName'], 'safe'],
            [['paket', 'vp_paid', 'debt','user'], 'safe'],
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
        $query = Pacients::find();
        $user_id = Yii::$app->user->id;
        $role = Yii::$app->user->identity->role;

        // add conditions that should always apply here
        $query->joinWith(['user']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort_ ([
            'attributes' => [


                'paket' => [
                    'asc' => ['paket' => SORT_ASC, 'paket' => SORT_ASC],
                    'desc' => ['paket' => SORT_DESC, 'paket' => SORT_DESC],
                    'label' => 'Пакет',
                    'default' => SORT_ASC
                ],
//                'vp_paid' => [
//                    'asc' => ['vp_paid' => SORT_ASC, 'vp_paid' => SORT_ASC],
//                    'desc' => ['vp_paid' => SORT_DESC, 'vp_paid' => SORT_DESC],
//                    'label' => 'Оплачено за ВП',
//                    'default' => SORT_ASC
//                ],
                'debt' => [
                    'asc' => ['debt' => SORT_ASC, 'debt' => SORT_ASC],
                    'desc' => ['debt' => SORT_DESC, 'debt' => SORT_DESC],
                    'label' => 'Оплачено за ВП',
                    'default' => SORT_ASC
                ],
            ]
        ]);

        $dataProvider->sort->attributes['user'] = [
            // Это те таблицы, с которыми у нас установлена связь
            'asc' => ['orto_user.fullname' => SORT_ASC],
            'desc' => ['orto_user.fullname' => SORT_DESC],
        ];


        if (!$this->load($params) && !$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /*$this->addCondition($query, 'paket', true);
        $this->addCondition($query, 'vp_paid', true);
        $this->addCondition($query, 'debt', true);*/

        if($role != 2 || $role != 4) {
            // grid filtering conditions
            $query->andFilterWhere([
                //'id' => $this->id,
                'doctor_id' => $user_id, // $this->doctor_id,
                'order_id' => $this->order_id,
                'vp_id' => $this->vp_id,
                'clinic_id' => $this->clinic_id,
                'code' => $this->code,
                'age' => $this->age,
                'date' => $this->date,
                'gender' => $this->gender,
                'status' => $this->status,
                'alert_date' => $this->alert_date,
                'type_paid' => $this->type_paid,
                'var_paid' => $this->var_paid,
                'product_id' => $this->product_id,
                'scull_top' => $this->scull_top,
                'scull_bottom' => $this->scull_bottom,
                'date_paid' => $this->date_paid,
            ]);
            // Фильтр по
            /* $query->joinWith(['user' => function ($q) {
                 $q->where('orto_user.fullname LIKE "%' . $this->fullName . '%"');
             }]);*/

            $query
                ->andFilterWhere(['like', 'alert_msg', $this->alert_msg])
                ->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'phone', $this->phone])
                ->andFilterWhere(['like', 'diagnosis', $this->diagnosis])
                ->andFilterWhere(['like', 'result', $this->result])
                ->andFilterWhere(['like', 'files', $this->files])
                ->andFilterWhere(['like', 'orto_user.fullname', $this->fullName])
                /*->andFilterWhere(['like', 'orto_pacients.paket', $this->paket])
                ->andFilterWhere(['like', 'orto_pacients.vp_paid', $this->vp_paid])
                ->andFilterWhere(['like', 'orto_pacients.debt', $this->debt])*/;

        }else{// для админов показать всех
            $query->andFilterWhere([
                'id' => $this->id,
                'doctor_id' => $this->doctor_id,
                'order_id' => $this->order_id,
                'vp_id' => $this->vp_id,
                'clinic_id' => $this->clinic_id,
                'code' => $this->code,
                'age' => $this->age,
                'date' => $this->date,
                'gender' => $this->gender,
                'status' => $this->status,
                'alert_date' => $this->alert_date,
                'type_paid' => $this->type_paid,
                'var_paid' => $this->var_paid,
                'product_id' => $this->product_id,
                'scull_top' => $this->scull_top,
                'scull_bottom' => $this->scull_bottom,
                'date_paid' => $this->date_paid,
            ]);
            //$query->andFilterWhere(['like', 'orto_pacients.paket', $this->paket]);

           /* $query->andFilterWhere(['like', 'orto_pacients.paket', $this->paket])
                ->andFilterWhere(['like', 'orto_pacients.vp_paid', $this->vp_paid])
                ->andFilterWhere(['like', 'orto_pacients.debt', $this->debt]);*/
        }


        return $dataProvider;
    }
    
    /*public function searchByDoctorOld($params)
    {
        $query = Pacients::find();

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
        $user_id = Yii::$app->user->id;

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'doctor_id' => $user_id,
            'order_id' => $this->order_id,
            'vp_id' => $this->vp_id,
            'clinic_id' => $this->clinic_id,
            'code' => $this->code,
            'age' => $this->age,
            'date' => $this->date,
            'gender' => $this->gender,
            'status' => $this->status,
            'alert_date' => $this->alert_date,
            'type_paid' => $this->type_paid,
            'var_paid' => $this->var_paid,
            'product_id' => $this->product_id,
            'scull_top' => $this->scull_top,
            'scull_bottom' => $this->scull_bottom,
            'date_paid' => $this->date_paid,
        ]);

        $query->andFilterWhere(['like', 'alert_msg', $this->alert_msg])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'diagnosis', $this->diagnosis])
            ->andFilterWhere(['like', 'result', $this->result])
            ->andFilterWhere(['like', 'files', $this->files]);

        return $dataProvider;
    }
  */
    public function searchByDoctor($params)
    {
        $query = Pacients::find();
/*
        $user_id = Yii::$app->user->id;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'paket' => [
                    'asc' => ['paket' => SORT_ASC ],
                    'desc' => ['paket' => SORT_DESC],
                    'label' => 'Пакет',
                    'default' => SORT_ASC
                ],
                'vp_paid' => [
                    'asc' => ['vp_paid' => SORT_ASC],
                    'desc' => ['vp_paid' => SORT_DESC],
                    'label' => 'Оплачено за ВП',
                    'default' => SORT_ASC
                ],
                'debt' => [
                    'asc' => ['debt' => SORT_ASC],
                    'desc' => ['debt' => SORT_DESC],
                    'label' => 'Оплачено за ВП',
                    'default' => SORT_ASC
                ],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }



       // $user_id = $params['pacient_id'];// Yii::$app->user->id;
//print_r($params['pacient_id']);
 //       exit;


        // $this->addCondition($query, 'vp_paid', true);

        // grid filtering conditions
        $query->andWhere([
            'id' => $params['pacient_id'], // по доктору
            //'doctor_id'=>$user_id,
            //'order_id' => $this->order_id,
            //'code' => $this->code,
        ]);

        $query->andFilterWhere(['like', 'orto_pacients.paket', $this->paket])
              ->andFilterWhere(['like', 'orto_pacients.vp_paid', $this->vp_paid])
              ->andFilterWhere(['like', 'orto_pacients.debt', $this->debt]);

        // фильтр по имени
        //$query->andWhere('`vp_paid` LIKE "%' . 'бесп' . '%" ');
        //$query->andWhere('`vp_paid` LIKE "%' . $this->vp_paid . '%" ');

        //return $dataProvider;
*/

        if ( ! $this->load($params) && $this->validate()) {

        }

        $query->andFilterWhere([
            'id' => $params['pacient_id'],
            // 'parent_id' => $this->parent_id,
        ]) ; //->andFilterWhere(['like', 'title', $this->title]);

        return new ArrayDataProvider([
            'allModels' => $query->all(),
            'sort' => [
                'attributes' => [
                    'name',
                    'vp_paid' => [
                        'asc' => ['paid' => SORT_ASC],
                        'desc' => ['paid' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'paket' => [
                        'asc' => ['paket' => SORT_ASC],
                        'desc' => ['paket' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'var_paid',
                    'date_paid',
                    'sum_paid',
                    'debt' => [
                        'asc' => ['debt' => SORT_ASC],
                        'desc' => ['debt' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                ],
            ],
        ]);
    }




}

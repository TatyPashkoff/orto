<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Delivery;

/**
 * DeliverySearch represents the model behind the search form about `backend\models\Delivery`.
 */
class DeliverySearch extends Delivery
{
    
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'pacient_id', 'delivery_ready', 'delivery_all'], 'integer'],
            [['date_delivery'], 'safe'],
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

        $user_id = Yii::$app->user->id;

        $query = Delivery::find();

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


        $order_ids = Orders::find()->select('id')->where(['doctor_id'=>$user_id])->asArray()->all();
        $order_id = [];
        foreach($order_ids as $order){
            $order_id[] = $order['id'];
        }
       // print_r($order_id);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'order_id' => $order_id, //$this->order_id,
            'pacient_id' => $this->pacient_id,
            'delivery_ready' => $this->delivery_ready,
            'delivery_all' => $this->delivery_all,
            'date_delivery' => $this->date_delivery,
        ]);

        return $dataProvider;
    }
}

<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\UserInfo;

/**
 * UserInfoSearch represents the model behind the search form about `common\models\UserInfo`.
 */
class UserInfoSearch extends UserInfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_type', 'category1', 'category2', 'category3', 'category4', 'category5', 'category6', 'category7', 'category8', 'category9', 'category10', 'category11', 'category12', 'category13', 'category14', 'category15', 'category16', 'category17', 'category18', 'category19', 'category20', 'category21', 'category22', 'category23', 'category24', 'pr1', 'pr2', 'pr3', 'pr4', 'social1', 'social2', 'social3', 'social4', 'social5', 'social6', 'type1', 'type2', 'type3', 'type4', 'type5', 'type6', 'type7'], 'integer'],
            //[['first_name', 'second_name', 'avatar', 'website', 'city', 'pr', 'socialUrl1', 'socialUrl2', 'socialUrl3', 'socialUrl4', 'socialUrl5', 'socialUrl6', 'partner', 'prDescr', 'contactPerson', 'contactEmail'], 'safe'],
            [['fullname', 'second_name', 'avatar', 'website', 'city', 'country', 'pr', 'socialUrl1', 'socialUrl2', 'socialUrl3', 'socialUrl4', 'socialUrl5', 'socialUrl6', 'partner', 'prDescr', 'contactPerson', 'contactEmail','phone'], 'safe'],
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
        $query = UserInfo::find();

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
            'id' => $this->id,
            'user_type' => $this->user_type,
            'category1' => $this->category1,
            'category2' => $this->category2,
            'category3' => $this->category3,
            'category4' => $this->category4,
            'category5' => $this->category5,
            'category6' => $this->category6,
            'category7' => $this->category7,
            'category8' => $this->category8,
            'category9' => $this->category9,
            'category10' => $this->category10,
            'category11' => $this->category11,
            'category12' => $this->category12,
            'category13' => $this->category13,
            'category14' => $this->category14,
            'category15' => $this->category15,
            'category16' => $this->category16,
            'category17' => $this->category17,
            'category18' => $this->category18,
            'category19' => $this->category19,
            'category20' => $this->category20,
            'category21' => $this->category21,
            'category22' => $this->category22,
            'category23' => $this->category23,
            'category24' => $this->category24,
            'pr1' => $this->pr1,
            'pr2' => $this->pr2,
            'pr3' => $this->pr3,
            'pr4' => $this->pr4,
            'social1' => $this->social1,
            'social2' => $this->social2,
            'social3' => $this->social3,
            'social4' => $this->social4,
            'social5' => $this->social5,
            'social6' => $this->social6,
            'type1' => $this->type1,
            'type2' => $this->type2,
            'type3' => $this->type3,
            'type4' => $this->type4,
            'type5' => $this->type5,
            'type6' => $this->type6,
            'type7' => $this->type7,
        ]);

        $query->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'second_name', $this->second_name])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'pr', $this->pr])
            ->andFilterWhere(['like', 'socialUrl1', $this->socialUrl1])
            ->andFilterWhere(['like', 'socialUrl2', $this->socialUrl2])
            ->andFilterWhere(['like', 'socialUrl3', $this->socialUrl3])
            ->andFilterWhere(['like', 'socialUrl4', $this->socialUrl4])
            ->andFilterWhere(['like', 'socialUrl5', $this->socialUrl5])
            ->andFilterWhere(['like', 'socialUrl6', $this->socialUrl6])
            ->andFilterWhere(['like', 'partner', $this->partner])
            ->andFilterWhere(['like', 'prDescr', $this->prDescr])
            ->andFilterWhere(['like', 'contactPerson', $this->contactPerson])
            ->andFilterWhere(['like', 'contactEmail', $this->contactEmail])
                ->andFilterWhere(['like', 'phone', $this->phone])
                ->andFilterWhere(['like', 'country', $this->country]);

        return $dataProvider;
    }
}

<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Pacients]].
 *
 * @see Pacients
 */
class PacientsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Pacients[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Pacients|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[Clinics]].
 *
 * @see Clinics
 */
class ClinicsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Clinics[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Clinics|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[ClinicsDoctors]].
 *
 * @see ClinicsDoctors
 */
class ClinicsDoctorsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ClinicsDoctors[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ClinicsDoctors|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

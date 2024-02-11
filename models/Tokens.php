<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tokens".
 *
 * @property int $id
 * @property string $password
 * @property int $max_allowed
 * @property int $used_today
 * @property int $total_used
 */
class Tokens extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tokens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'max_allowed', 'used_today', 'total_used'], 'required'],
            [['max_allowed', 'used_today', 'total_used'], 'integer'],
            [['password'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'password' => 'Password',
            'max_allowed' => 'Max Allowed',
            'used_today' => 'Used Today',
            'total_used' => 'Total Used',
        ];
    }

    /**
     * Gets query for [[Devices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDevices()
    {
        return $this->hasMany(Devices::className(), ['token_id' => 'id']);
    }

}

<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "devices".
 *
 * @property int $id
 * @property string|null $mac
 * @property int $ip
 * @property int $prev_usage
 * @property int $total_usage
 * @property int|null $token_id
 */
class Devices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'devices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip', 'prev_usage', 'total_usage'], 'required'],
            [['prev_usage', 'total_usage', 'token_id'], 'integer'],
            [['mac'], 'string', 'max' => 50],
            [['ip'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mac' => 'Mac',
            'ip' => 'Ip',
            'prev_usage' => 'Prev Usage',
            'total_usage' => 'Total Usage',
            'token_id' => 'Token ID',
        ];
    }

    /**
     * Gets query for [[Token]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getToken()
    {
        return $this->hasOne(\app\models\Tokens::className(), ['id' => 'token_id']);
    }
}

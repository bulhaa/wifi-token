<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pieces".
 *
 * @property int $id
 * @property int $type_id
 * @property int $player_id
 * @property int|null $x
 * @property int|null $y
 * @property int $level
 *
 * @property Moves[] $moves
 * @property Moves[] $moves0
 * @property Users $player
 */
class Pieces extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pieces';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_id', 'player_id', 'level'], 'required'],
            [['type_id', 'player_id', 'x', 'y', 'level'], 'integer'],
            [['player_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['player_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_id' => 'Type ID',
            'player_id' => 'Player ID',
            'x' => 'X',
            'y' => 'Y',
            'level' => 'Level',
        ];
    }

    /**
     * Gets query for [[Moves]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMoves()
    {
        return $this->hasMany(Moves::className(), ['from_piece_id' => 'id']);
    }

    /**
     * Gets query for [[Moves0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMoves0()
    {
        return $this->hasMany(Moves::className(), ['to_piece_id' => 'id']);
    }

    /**
     * Gets query for [[Player]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlayer()
    {
        return $this->hasOne(Users::className(), ['id' => 'player_id']);
    }
}

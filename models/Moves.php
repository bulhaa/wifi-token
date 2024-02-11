<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "moves".
 *
 * @property int $id
 * @property int|null $from_x
 * @property int|null $from_y
 * @property int $to_x
 * @property int $to_y
 * @property int $from_piece_id
 * @property int|null $to_piece_id
 * @property string|null $time
 *
 * @property Pieces $fromPiece
 * @property Pieces $toPiece
 */
class Moves extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'moves';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from_x', 'from_y', 'to_x', 'to_y', 'from_piece_id', 'to_piece_id'], 'integer'],
            [['to_x', 'to_y', 'from_piece_id'], 'required'],
            [['time'], 'safe'],
            [['from_piece_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pieces::className(), 'targetAttribute' => ['from_piece_id' => 'id']],
            [['to_piece_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pieces::className(), 'targetAttribute' => ['to_piece_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from_x' => 'From X',
            'from_y' => 'From Y',
            'to_x' => 'To X',
            'to_y' => 'To Y',
            'from_piece_id' => 'From Piece ID',
            'to_piece_id' => 'To Piece ID',
            'time' => 'Time',
        ];
    }

    /**
     * Gets query for [[FromPiece]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFromPiece()
    {
        return $this->hasOne(Pieces::className(), ['id' => 'from_piece_id']);
    }

    /**
     * Gets query for [[ToPiece]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getToPiece()
    {
        return $this->hasOne(Pieces::className(), ['id' => 'to_piece_id']);
    }
}

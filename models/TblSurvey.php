<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_survey".
 *
 * @property integer $id
 * @property string $title
 * @property integer $parent_id
 * @property string $stype
 * @property integer $vote1
 * @property integer $vote2
 * @property integer $vote3
 * @property integer $vote4
 * @property integer $vote5
 * @property string $comment
 */
class TblSurvey extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_survey';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'parent_id'], 'required'],
            [['parent_id', 'vote1', 'vote2', 'vote3', 'vote4', 'vote5'], 'integer'],
            [['comment'], 'string'],
            [['title'], 'string', 'max' => 300],
            [['stype'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'parent_id' => 'Parent ID',
            'stype' => 'Stype',
            'vote1' => 'Vote1',
            'vote2' => 'Vote2',
            'vote3' => 'Vote3',
            'vote4' => 'Vote4',
            'vote5' => 'Vote5',
            'comment' => 'Comment',
        ];
    }
}

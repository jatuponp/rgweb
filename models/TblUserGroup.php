<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_user_group".
 *
 * @property integer $id
 * @property string $title
 */
class TblUserGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 250]
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
        ];
    }
    
    public function makeDropDown($_lang = 'thai') {
        //$_lang = ($this->langs)? $this->langs : 'thai';
        $query = TblUserGroup::find();
        $result = $query->all();
        foreach ($result as $m) {
            $data[$m->id] = $m->title;
        }

        return $data;
    }
}

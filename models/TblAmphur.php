<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_amphur".
 *
 * @property integer $id
 * @property string $names
 * @property string $names_eng
 */
class TblAmphur extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_amphur';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['names'], 'required'],
            [['names'], 'string', 'max' => 250],
            [['names_eng'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'names' => 'Names',
            'names_eng' => 'Names Eng',
        ];
    }
    
    public function makeDropDown() {
        global $data;
        $data = array();
        $parents = TblAmphur::find()->all();
        $data[''] = Yii::t('app', 'Select Amphoe');
        $_lang = \app\components\langs::getLang();
        foreach ($parents as $parent) {
            $data[$parent->id] = (($_lang == 'thai')? $parent->names:$parent->names_eng);
        }

        return $data;
    }
}

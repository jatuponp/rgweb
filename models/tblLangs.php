<?php

namespace app\models;

/**
 * This is the model class for table "tbl_langs".
 *
 * @property string $langs
 * @property string $langauage
 * @property string $longs
 */
class tblLangs extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_langs';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['langs', 'langauage', 'longs'], 'required'],
            [['langs'], 'string', 'max' => 50],
            [['langauage', 'longs'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'langs' => 'Langs',
            'langauage' => 'Langauage',
            'longs' => 'Longs',
        ];
    }
    
    public function makeDropDown() {
        global $data;
        $data = array();
        $parents = tblLangs::find()->all();
        foreach ($parents as $parent) {
            $data[$parent->langs] = $parent->longs;
        }

        return $data;
    }

}

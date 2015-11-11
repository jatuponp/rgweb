<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_slidertype".
 *
 * @property integer $id
 * @property string $title
 */
class TblSlidertype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_slidertype';
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
            'title' => 'เพิ่มประเภทไสลด์',
        ];
    }
    
    public function search() {

        $dataProvider = new ActiveDataProvider([
            'query' => $this->find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }
    
    public function makeDropDown() {
        $model = TblSlidertype::find()->all();
        foreach ($model as $m) {
            $data[$m->id] = $m->title;
        }

        return $data;
    }
}

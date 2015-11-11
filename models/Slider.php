<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_slider".
 *
 * @property integer $id
 * @property string $slider_Url
 * @property string $link_Url
 * @property integer $target
 * @property integer $published
 * @property integer $ordering
 * @property string $submitdate
 */
class Slider extends \yii\db\ActiveRecord {

    public $upload_files;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_slider';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['slider_Url'], 'required'],
            [['cid','target', 'published', 'ordering'], 'integer'],
            [['title','fulltexts', 'positions'], 'string'],
            [['submitdate'], 'safe'],
            [['slider_Url', 'link_Url'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'cid' => 'หมวดหมู่ภาพสไลด์',
            'slider_Url' => 'Slider  Url',
            'link_Url' => 'Link  Url',
            'target' => 'Target',
            'published' => 'Published',
            'ordering' => 'Ordering',
            'submitdate' => 'Submitdate',
            'title' => 'หัวข้อ',
            'fulltexts' => 'รายละเอียด', 
            'positions' => 'ตำแหน่ง'
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $now = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {
                $this->published = 0;
                $this->submitdate = $now;
                if (!$this->langs) {
                    $this->langs = 'thai';
                }
            }
            return true;
        }
        return false;
    }

    public function search() {
        $langs = $this->langs;
        $query = Slider::find();
        $query->where(['cid' => $this->cid, 'langs' => $langs]);
        $query->orderBy('cid, ordering ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }

    public function slider($cid = 1) {

        $query = Slider::find();
        $query->where(['cid' => $cid, 'langs' => 'thai', 'published' => 1]);
        $query->orderBy('ordering ASC');
        $result = $query->all();

        return $result;
    }

    public function orderMax($langs = null, $cid = null) {
        if ($langs == null)
            $langs = 'thai';
        $query = Slider::find()->where(['cid' => $cid, 'langs' => $langs])->max('ordering');
        return $query;
    }

    public function orderMin($langs = null, $cid = null) {
        if ($langs == null)
            $langs = 'thai';
        $query = Slider::find()->where(['cid' => $cid, 'langs' => $langs])->min('ordering');
        return $query;
    }

}

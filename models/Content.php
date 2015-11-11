<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_content".
 *
 * @property string $id
 * @property string $title
 * @property string $fulltexts
 * @property integer $published
 * @property string $submitDate
 * @property string $applyDate
 */
class Content extends ActiveRecord {
    public $search;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_content';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['title'], 'required'],
            [['fulltexts', 'langs'], 'string'],
            [['published'], 'integer'],
            [['submitDate', 'applyDate'], 'safe'],
            [['title'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'ชื่อเรื่อง',
            'fulltexts' => 'เนื้อหา',
            'published' => 'เผยแพร่',
            'submitDate' => 'วันที่สร้าง',
            'applyDate' => 'วันที่ปรับปรุง',
            'langs' => 'ภาษา',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            date_default_timezone_set('ICT');
            $now = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {
                $this->published = 0;
                $this->submitDate = $now;
                $this->applyDate = $now;
                $this->langs = 'thai';
            }else{
                $this->applyDate = $now;
            }
            return true;
        }
        return false;
    }

    public function search() {
        $search = $_POST['Content']['search'];
        $query = Content::find()->where('title LIKE :s',[':s'=>"%$search%"])->orderBy('id DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }
    
    public function makeDropDown($langs = null) {
        global $data;
        $langs = ($langs)? $langs:'thai';
        $data = array();
        $parents = Content::find()
                ->where(['langs' => $langs])
                ->all()
        ;
        foreach ($parents as $parent) {
            $data[$parent->id] = $parent->title;
        }

        return $data;
    }

}

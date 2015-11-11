<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_categories".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $title
 * @property string $description
 * @property boolean $published
 * @property string $langs
 */
class Categories extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['parent_id', 'title'], 'required'],
            [['parent_id'], 'integer'],
            [['description', 'langs'], 'string'],
            [['published'], 'boolean'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'parent_id' => 'หมวดหมู่หลัก',
            'title' => 'ชื่อหมวดหมู่',
            'description' => 'รายละเอียด',
            'published' => 'Published',
            'langs' => 'ภาษา',
        ];
    }

    public function search() {
        $langs = ($_POST['Categories']['langs']) ? $_POST['Categories']['langs'] : 'thai';
        $query = Categories::find()->where(['langs' => $langs])->orderBy('id');
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
        $data = array();
        $data['0'] = '-- Top Level --';
        if ($langs == null) {
            $langs = ($_POST['Categories']['langs']) ? $_POST['Categories']['langs'] : ($_REQUEST['langs']) ? $_REQUEST['langs'] : 'thai';
        }
        $parents = Categories::find()
                ->where(['parent_id' => 0, 'langs' => $langs])
                ->all()
        ;
        foreach ($parents as $parent) {
            $data[$parent->id] = $parent->title;
            Categories::subDropDown($parent->id);
        }

        return $data;
    }

    public function subDropDown($parent, $space = '|---') {
        global $data;

        $children = Categories::find()->where(['parent_id' => $parent])->all();
        foreach ($children as $child) {
            $data[$child->id] = $space . ' ' . $child->title;
            Categories::subDropDown($child->id, $space . '---');
        }
    }

    public function listCategory($langs = null) {
        global $arr;
        //$data = array();
        $arr = array();
        if ($langs == null) {
            $langs = ($_POST['Categories']['langs']) ? $_POST['Categories']['langs'] : 'thai';
        }
        $cat = ($_POST['Categories']['parent_id']) ? $_POST['Categories']['parent_id'] : '0';
        $parents = Categories::find()
                ->where(['parent_id' => $cat, 'langs' => $langs])
                ->all()
        ;
        foreach ($parents as $parent) {
            $data = array();
            $data['id'] = $parent->id;
            $data['title'] = $parent->title;
            $arr[] = $data;
            Categories::listCategorySub($parent->id);
        }

        return new ArrayDataProvider([
            'allModels' => $arr,
            'key' => 'id',
            'pagination' => [
                'pageSize' => 15,
        ]]);
    }

    public function listCategorySub($parent, $space = '|---') {
        global $arr;

        $children = Categories::find()->where(['parent_id' => $parent])->all();
        foreach ($children as $child) {
            $data = array();
            $data['id'] = $child->id;
            $data['title'] = $space . ' ' . $child->title;
            $arr[] = $data;
            Categories::listCategorySub($child->id, $space . '---');
        }
    }

}

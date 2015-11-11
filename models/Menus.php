<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use app\models\TblMenutype;

/**
 * This is the model class for table "tbl_menus".
 *
 * @property integer $id
 * @property string $names
 * @property integer $parent_id
 * @property string $urls
 * @property string $langs
 * @property integer $published
 * @property integer $ordering
 */
class Menus extends ActiveRecord {

    public $content;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_menus';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['names', 'type'], 'required'],
            [['type', 'published', 'ordering', 'target'], 'integer'],
            [['names', 'urls', 'description'], 'string', 'max' => 255],
            [['icons'], 'string', 'max' => 100],
            [['langs'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'type' => 'หมวดหมู่',
            'names' => 'ชื่อเมนู',
            'parent_id' => 'ภายใต้เมนู',
            'urls' => 'ที่อยู่ URL',
            'langs' => 'ภาษา',
            'content' => 'เชื่อมโยงเนื้อหา',
            'published' => 'Published',
            'ordering' => 'Ordering',
            'pics' => 'เลือกภาพ ICON เพิ่มเติม',
            'description' => 'หมายเหตุ'
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                //$this->langs = 'thai';
//                if(!$this->parent_id){
//                    $this->parent_id = 0;
//                }
            }
            return true;
        }
        return false;
    }

    public function search() {
        //$langs = ($this->langs) ? $this->langs : 'thai';
        $query = Menus::find()->where(['type' => $this->type])->orderBy('ordering');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }

    private function orderMax($langs = null, $type = null, $parent_id = null) {
        if ($langs == null)
            $langs = 'thai';
        $query = Menus::find()
                ->where(['langs' => $langs, 'type' => $type, 'parent_id' => $parent_id])
                ->max('ordering');
        return $query;
    }

    private function orderMin($langs = null, $type = null, $parent_id = null) {
        if ($langs == null)
            $langs = 'thai';
        $query = Menus::find()
                ->where(['langs' => $langs, 'type' => $type, 'parent_id' => $parent_id])
                ->min('ordering');

        return $query;
    }

    public function makeDropDown($langs = null) {
        global $data;
        $langs = ($langs) ? $langs : 'thai';
        $data = array();
        $data['0'] = '-- Top Level --';
        $parents = Menus::find()
                ->where(['parent_id' => 0, 'langs' => $langs])
                ->all()
        ;
        foreach ($parents as $parent) {
            $data[$parent->id] = $parent->names;
            Menus::subDropDown($parent->id);
        }

        return $data;
    }

    public function subDropDown($parent, $space = '|---') {
        global $data;

        $children = Menus::find()->where(['parent_id' => $parent])->all();
        foreach ($children as $child) {
            $data[$child->id] = $space . ' ' . $child->names;
            Menus::subDropDown($child->id, $space . '---');
        }
    }

    public function listCategory($langs = 'thai') {
        global $arr;
        $arr = array();
        $parents = Menus::find()->where(['parent_id' => 0, 'langs'=>$langs]);
        if ($this->type) {
            $parents = $parents->andWhere(['type' => $this->type]);
        } else {
            $menu = TblMenutype::makeDropDown($langs);
            foreach ($menu as $key => $value){
                $mk = $key;
                break;
            }
            $parents = $parents->andWhere(['type' => $mk]);
        }
        $parents = $parents->orderBy('ordering')->all();
        foreach ($parents as $parent) {
            $data = array();
            $data['id'] = $parent->id;
            $data['names'] = $parent->names;
            $data['published'] = $parent->published;
            $data['ordering'] = $parent->ordering;
            $data['min'] = $this->orderMin($parent->langs, $parent->type, $parent->parent_id);
            $data['max'] = $this->orderMax($parent->langs, $parent->type, $parent->parent_id);
            $arr[] = $data;
            Menus::listCategorySub($parent->id);
        }

        return new ArrayDataProvider([
            'allModels' => $arr,
            'key' => 'id',
            'pagination' => [
                'pageSize' => 20,
        ]]);
    }

    public function listCategorySub($parent, $space = '|---') {
        global $arr;

        $children = Menus::find()
                ->where(['parent_id' => $parent])
                ->orderBy('ordering')
                ->all();
        foreach ($children as $child) {
            $data = array();
            $data['id'] = $child->id;
            $data['names'] = $space . ' ' . $child->names;
            $data['published'] = $child->published;
            $data['ordering'] = $child->ordering;
            $data['min'] = $this->orderMin($child->langs, $child->type, $child->parent_id);
            $data['max'] = $this->orderMax($child->langs, $child->type, $child->parent_id);
            $arr[] = $data;
            Menus::listCategorySub($child->id, $space . '---');
        }
    }

    public function listMenus($parent, $level, $type) {
        $connection = \Yii::$app->db;
        $sql = "SELECT a.id, a.names, a.icons, a.urls, a.type, a.target, Deriv1.Count FROM `tbl_menus` a  "
                . "LEFT OUTER JOIN (SELECT parent_id, COUNT(*) AS Count FROM `tbl_menus` GROUP BY parent_id) "
                . "Deriv1 ON a.id = Deriv1.parent_id WHERE a.parent_id=" . $parent . " AND a.published=1 AND "
                . "a.type=" . $type . " ORDER BY a.ordering";
        ;
        $command = $connection->createCommand($sql);
        $reader = $command->query();
        $data = array();
        foreach ($reader as $r) {
            if ($r['Count'] > 0) {
                $data['label'] = '<i class="' . $r['icons'] . '"></i> ' . $r['names'];
                $data['url'] = (($r['article_id']) ? ['default/view', 'id' => $r['article_id']] : $r['urls']);
                $data['items'] = Menus::listMenus($r['id'], $level + 1, $type);
            } else {
                $data['label'] = '<i class="' . $r['icons'] . '"></i> ' . $r['names'];
                $htt = substr($r['urls'], 0, 4);
                $data['url'] = (($htt == 'http')? $r['urls']:$r['urls']);
                if($r['target'] == 1){
                    $data['linkOptions'] = ['target' => '_blank'];
                }
                unset($data['items']);
            }

            $items[] = $data;
        }

        return $items;
    }

}

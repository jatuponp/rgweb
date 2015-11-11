<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_documents".
 *
 * @property integer $id
 * @property integer $cid
 * @property string $title
 * @property string $file_name
 * @property string $file_new
 * @property string $file_update
 * @property integer $ordering
 * @property integer $published
 * @property integer $createby
 */
class TblDocuments extends \yii\db\ActiveRecord {

    public $search;
    public $langs;
    public $import;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_documents';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['cid', 'title'], 'required'],
            [['cid', 'ordering', 'published', 'createby'], 'integer'],
            [['file_update'], 'safe'],
            [['title'], 'string', 'max' => 255],
            //[['file_name', 'file_new'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'cid' => 'หมวดหมู่',
            'title' => 'ชื่อเอกสาร',
            'file_name' => 'File Name',
            'file_new' => 'File New',
            'file_update' => 'File Update',
            'ordering' => 'Ordering',
            'published' => 'Published',
            'createby' => 'Createby',
            'import' => 'ไฟล์เอกสาร'
        ];
    }

    public function getCategorie() {
        return $this->hasOne(Categories::className(), ['id' => 'cid']);
    }

    public function getCatname() {
        return $this->categorie->title;
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $now = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {
                $this->published = 0;
                $this->file_update = $now;
                $this->createby = \Yii::$app->user->id;
            }
            return true;
        }
        return false;
    }

    public function search() {
        $search = $this->search; //($_POST['Article']['search'])? $_POST['Article']['search']:($_REQUEST['search'])? $_REQUEST['search']:'';
        $langs = $this->langs;
        $cid = $this->cid;
        $query = $this->find()->where('title LIKE :s', [':s' => "%$search%"]);
        if ($langs)
            $query->andWhere(['langs' => $langs]);
        if ($cid)
            $query->andWhere(['cid' => $cid]);

//        $auth = \Yii::$app->authManager->getAssignments(\Yii::$app->user->id);
//        if ($auth['Editor']->roleName == 'Editor' || $auth['Publisher']->roleName == 'Publisher') {
//            $q = User::find()->where(['gid' => \Yii::$app->user->id])->all();
//            foreach ($q as $r) {
//                $gid[] = $r->id;
//            }
//            $query->andWhere('createBy IN (' . implode(',', $gid) . ')');
//        }

        $query->orderBy('cid, ordering ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $dataProvider;
    }

    public function orderMax($langs = null, $cid = null) {
        $query = $this->find()->where(['cid' => $cid])->max('ordering');
        return $query;
    }

    public function orderMin($langs = null, $cid = null) {
        $query = $this->find()->where(['cid' => $cid])->min('ordering');
        return $query;
    }   
    

}

<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_article".
 *
 * @property integer $id
 * @property integer $cid
 * @property string $title
 * @property string $fulltexts
 * @property integer $ordering
 * @property integer $published
 * @property string $startdate
 * @property string $finishdate
 * @property string $submitdate
 * @property string $applydate
 * @property string $langs
 * @property string $pins
 * @property integer $frontpage
 */
class Article extends \yii\db\ActiveRecord {

    public $search;
    public $upload_files;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_article';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['cid', 'title'], 'required'],
            [['cid', 'ordering', 'published',], 'integer'],
            [['fulltexts', 'langs', 'search'], 'string'],
            [['startdate', 'finishdate', 'submitdate', 'applydate'], 'safe'],
            [['title'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'cid' => 'หมวดหมู่บทความ',
            'title' => 'ชื่อเรื่อง',
            'fulltexts' => 'Fulltexts',
            'ordering' => 'Ordering',
            'published' => 'การเผยแพร่',
            'startdate' => 'เริ่มวันที่',
            'finishdate' => 'สิ้นสุดวันที่',
            'submitdate' => 'Submitdate',
            'applydate' => 'Applydate',
            'langs' => 'ภาษา',
            'upload_files' => 'ภาพประกอบ'
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $now = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {
                $auth = \Yii::$app->authManager->getAssignments(\Yii::$app->user->id);
                if ($auth['Editor']->roleName == 'Editor') {
                    $this->published = 0;
                }
                $this->submitdate = $now;
                $this->applydate = $now;
                $this->createBy = \Yii::$app->user->id;
                if (!$this->startdate) {
                    $this->startdate = '0000-00-00';
                }
                if (!$this->finishdate) {
                    $this->finishdate = '0000-00-00';
                }
                if (!$this->langs) {
                    $this->langs = 'thai';
                }
            } else {
                $this->applydate = $now;
            }
            return true;
        }
        return false;
    }

    public function getCatName($cid) {
        $cat = Categories::findOne($cid);
        return $cat->title;
    }

    public function search() {
        $search = $this->search; //($_POST['Article']['search'])? $_POST['Article']['search']:($_REQUEST['search'])? $_REQUEST['search']:'';
        $langs = $this->langs;
        $cid = $this->cid;
        $query = Article::find();
        if ($search)
            $query->where('title LIKE :s', [':s' => "%$search%"]);

        if ($langs)
            $query->andWhere(['langs' => $langs]);
        if ($cid)
            $query->andWhere(['cid' => $cid]);

        $auth = \Yii::$app->authManager->getAssignments(\Yii::$app->user->id);
        if ($auth['Editor']->roleName == 'Editor' || $auth['Publisher']->roleName == 'Publisher') {
            $q = User::find()->where(['gid' => \Yii::$app->user->identity->gid])->all();
            foreach ($q as $r) {
                $gid[] = $r->id;
            }
            $query->andWhere('createBy IN (' . implode(',', $gid) . ')');
        }

        $query->orderBy('cid, ordering ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }

    public function news($cid = null, $limit = null) {
        $langs = $this->langs;
        $now = date('Y-m-d');
        $query = Article::find()->where(['cid' => $cid, 'published' => 1]);
        $query->andWhere(['OR', 'startdate = ' . "'0000-00-00'", "startdate <='" . $now . "'"]);
        $query->andWhere(['OR', 'finishdate = ' . "'0000-00-00'", "finishdate >='" . $now . "'"]);
        if ($langs):
            $query->andWhere(['langs' => $langs]);
        endif;

        $query->orderBy('ordering ASC');
        if($limit):
            $query->limit($limit);
        endif;
        $result = $query->all();

        return $result;
    }

    public function frontNews() {
        $langs = \app\components\langs::getLang();
        $now = date('Y-m-d');
        $_lan = [$langs, 'english', 'thai']; //ค้นหาข่าวในภาษานั้น ๆ ก่อนถ้าไม่เจอข่าว ให้ค้นหาในภาษาอังกฤษ ภาษาไทย ตามลำดับ
        $cat = ['thai' => 2, 'english' => 7];
        foreach ($_lan as $l) {
            $cid = $cat[$l];
            $query = Article::find()->where(['cid' => $cid, 'langs' => $l, 'published' => 1]);
            $query->andWhere(['OR', 'startdate = ' . "'0000-00-00'", "startdate <='" . $now . "'"]);
            $query->andWhere(['OR', 'finishdate = ' . "'0000-00-00'", "finishdate >='" . $now . "'"]);
            $query->orderBy('ordering ASC');
            $result = $query->limit(3)->all();
            
            //ถ้าเจอข่าวมากกว่า 1 ออกจากการค้นหาข่าว
            if(count($result) > 0){
                break;
            }
        }

        return $result;
    }
    
    public function emagNews() {
        $langs = \app\components\langs::getLang();
        $now = date('Y-m-d');
        $_lan = [$langs, 'english', 'thai']; //ค้นหาข่าวในภาษานั้น ๆ ก่อนถ้าไม่เจอข่าว ให้ค้นหาในภาษาอังกฤษ ภาษาไทย ตามลำดับ
        $cat = ['thai' => 12, 'english' => 12];
        foreach ($_lan as $l) {
            $cid = $cat[$l];
            $query = Article::find()->where(['cid' => $cid, 'langs' => $l, 'published' => 1]);
            $query->andWhere(['OR', 'startdate = ' . "'0000-00-00'", "startdate <='" . $now . "'"]);
            $query->andWhere(['OR', 'finishdate = ' . "'0000-00-00'", "finishdate >='" . $now . "'"]);
            $query->orderBy('ordering ASC');
            $result = $query->limit(3)->all();
            
            //ถ้าเจอข่าวมากกว่า 1 ออกจากการค้นหาข่าว
            if(count($result) > 0){
                break;
            }
        }

        return $result;
    }
    
    public function eventNews($limit = 4) {
        $langs = \app\components\langs::getLang();
        $now = date('Y-m-d');
        $_lan = [$langs, 'english', 'thai']; //ค้นหาข่าวในภาษานั้น ๆ ก่อนถ้าไม่เจอข่าว ให้ค้นหาในภาษาอังกฤษ ภาษาไทย ตามลำดับ
        $cat = ['thai' => 3, 'english' => 8];
        foreach ($_lan as $l) {
            $cid = $cat[$l];
            $query = Article::find()->where(['cid' => $cid, 'langs' => $l, 'published' => 1]);
            //$query->andWhere(['OR', 'startdate = ' . "'0000-00-00'", "startdate <='" . $now . "'"]);
            //$query->andWhere(['OR', 'finishdate = ' . "'0000-00-00'", "finishdate >='" . $now . "'"]);
            $query->orderBy('startdate ASC');
            $result = $query->limit($limit)->all();
            
            //ถ้าเจอข่าวมากกว่า 1 ออกจากการค้นหาข่าว
            if(count($result) > 0){
                break;
            }
        }

        return $result;
    }

    public function checkOwner($id) {
        $auth = \Yii::$app->authManager->getAssignments(\Yii::$app->user->id);
        //print_r($auth);
        $access = false;
        if ($auth['Editor']->roleName == 'Editor') {
            $model = Article::findOne($id);
            if ($model->createBy == \Yii::$app->user->id) {
                $access = true;
            }
        } else {
            $access = true;
        }
        return $access;
    }

    public function orderMax($langs = null, $cid = null) {
        if ($langs == null)
            $langs = 'thai';
        $query = Article::find()->where(['langs' => $langs, 'cid' => $cid])->max('ordering');
        return $query;
    }

    public function orderMin($langs = null, $cid = null) {
        if ($langs == null)
            $langs = 'thai';
        $query = Article::find()->where(['langs' => $langs, 'cid' => $cid])->min('ordering');
        return $query;
    }

    public function makeLink($langs = null) {
        global $data;
        $langs = ($langs) ? $langs : 'thai';
        $data = array();
        $parents = Article::find()
                ->where(['langs' => $langs, 'published' => 1])
                ->orderBy('ordering ASC')
                ->all()
        ;
        foreach ($parents as $parent) {
            $data[\yii\helpers\Url::to(['/site/content', 'id' => $parent->id])] = '- ' . $parent->title;
        }

        return $data;
    }
    
    public function searchCalendarAll() {
        $arr = array();
        $langs = \app\components\langs::getLang();
        $_lan = [$langs, 'english', 'thai']; //ค้นหาข่าวในภาษานั้น ๆ ก่อนถ้าไม่เจอข่าว ให้ค้นหาในภาษาอังกฤษ ภาษาไทย ตามลำดับ
        $cat = ['thai' => 3, 'english' => 8];
        foreach ($_lan as $l) {
            $cid = $cat[$l];
            $query = Article::find()->where(['cid' => $cid, 'langs' => $l, 'published' => 1]);
            $query->orderBy('startdate ASC');
            $result = $query->all();
            
            //ถ้าเจอข่าวมากกว่า 1 ออกจากการค้นหาข่าว
            if(count($result) > 0){
                break;
            }
        }

        foreach ($result as $r) {
            $arr[] = array('title' => $r->title, 'start' => $r->startdate, 'end' => $r->finishdate, 'url' => \yii\helpers\Url::to(['site/view', 'id' => $r->id]), 'allDay' => false);
        }
        return $arr;
    }

}

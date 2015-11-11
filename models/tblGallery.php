<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_gallery".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $submitdate
 * @property string $applydate
 * @property string $langs
 */
class tblGallery extends \yii\db\ActiveRecord
{
    public $search;
    public $upload_files;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['amphur'], 'integer'],
            [['description'], 'string'],
            [['submitdate', 'applydate'], 'safe'],
            [['title'], 'string', 'max' => 250],
            [['langs'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'amphur' => 'อำเภอ',
            'title' => 'ชื่ออัลบั้ม',
            'description' => 'รายละเอียด',
            'submitdate' => 'Submitdate',
            'applydate' => 'Applydate',
            'langs' => 'ภาษา',
        ];
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $now = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {
                $this->published = 0;
                $this->submitdate = $now;
                $this->applydate = $now;
                if(!$this->langs){
                    $this->langs = 'thai';
                }
            } else {
                $this->applydate = $now;
            }
            return true;
        }
        return false;
    }
    
    public function search() {
        $search = $this->search;
        $langs = $this->langs;
        $amphur = $this->amphur;
        $query = tblGallery::find()->where('title LIKE :s', [':s' => "%$search%"]);
        if ($langs){
            $query->andWhere(['langs' => $langs]);
        }
        if ($amphur){
            $query->andWhere(['amphur' => $amphur]);
        }

        $query->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }
    
    public function lists($amphur = null,$limit = '3') {
        $search = $this->search;
        $langs = $this->langs;
        //$amphur = $this->amphur;
        $query = tblGallery::find()->where('title LIKE :s', [':s' => "%$search%"]);
        if ($langs){
            $query->andWhere(['langs' => $langs]);
        }
        if ($amphur){
            $query->andWhere(['amphur' => $amphur]);
        }

        $query->orderBy('id DESC');
        $query->limit($limit);

        $result = $query->all();

        return $result;
    }
}

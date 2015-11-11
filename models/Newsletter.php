<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_newsletter".
 *
 * @property integer $id
 * @property string $years
 * @property string $volumn
 * @property string $images
 * @property string $files
 * @property integer $ordering
 * @property integer $published
 * @property string $submitdate
 * @property string $applydate
 */
class Newsletter extends \yii\db\ActiveRecord
{
    public $langs;
    public $cid;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_newsletter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['years', 'volumn'], 'required'],
            [['ordering', 'published'], 'integer'],
            [['submitdate', 'applydate'], 'safe'],
            [['years', 'volumn'], 'string', 'max' => 100],
//            [['images'], 'file', 'types' => 'jpg,jpeg,gif,png'],
//            [['files'], 'file', 'types' => 'pdf'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'years' => 'ปีที่',
            'volumn' => 'ฉบับที่',
            'images' => 'ภาพตัวอย่างจดหมายข่าว',
            'files' => 'ไฟล์จดหมายข่าว',
            'ordering' => 'Ordering',
            'published' => 'Published',
            'submitdate' => 'Submitdate',
            'applydate' => 'Applydate',
        ];
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $now = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {
                $this->published = 1;
                $this->submitdate = $now;
                $this->applydate = $now;
            }else{
                $this->applydate = $now;
            }
            return true;
        }
        return false;
    }
    
    public function search() {
        $query = Newsletter::find();
        $query->orderBy('ordering ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }
    
    public function lists() {
        $query = Newsletter::find()->where(['published' => 1]);
        $query->orderBy('ordering ASC');
        $query->limit(1);

        $result = $query->all();

        return $result;
    }
    
    public function orderMax($langs = null, $cid = null) {
        $query = Newsletter::find()->max('ordering');
        return $query;
    }

    public function orderMin($langs = null, $cid = null) {
        $query = Newsletter::find()->min('ordering');
        return $query;
    }
}

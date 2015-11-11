<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_clips".
 *
 * @property integer $id
 * @property string $titles
 * @property string $vdo
 * @property integer $ordering
 * @property integer $published
 * @property string $submitDate
 */
class Clips extends \yii\db\ActiveRecord
{
    public $langs;
    public $cid;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_clips';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['titles', 'vdo'], 'required'],
            [['ordering', 'published'], 'integer'],
            [['submitDate'], 'safe'],
            [['titles', 'vdo'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'titles' => 'ชื่อวิดีโอ',
            'vdo' => 'Youtube Video URL',
            'ordering' => 'Ordering',
            'published' => 'Published',
            'submitDate' => 'Submit Date',
        ];
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $now = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {
                $this->published = 0;
                $this->submitDate = $now;
            }
            return true;
        }
        return false;
    }
    
    public function search() {
        $query = Clips::find();
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
        $query = Clips::find()->where(['published'=>1]);
        $query->orderBy('ordering ASC');
        $query->limit(3);

        $result = $query->all();

        return $result;
    }
    
    public function orderMax($langs = null, $cid = null) {
        $query = Clips::find()->max('ordering');
        return $query;
    }

    public function orderMin($langs = null, $cid = null) {
        $query = Clips::find()->min('ordering');
        return $query;
    }
    
}

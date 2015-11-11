<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_menutype".
 *
 * @property integer $id
 * @property string $title
 */
class TblMenutype extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_menutype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
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
            'title' => 'ชื่อประเภท',
        ];
    }
    
    public function search($_lang = 'thai') {

        $dataProvider = new ActiveDataProvider([
            'query' => $this->find()->where(['langs'=>$_lang]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }
    
    public function makeDropDown($_lang = 'thai') {
        //$_lang = ($this->langs)? $this->langs : 'thai';
        $query = TblMenutype::find();
        $role = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
        if($role["Editor"]->name == 'Editor' || $role["Publisher"]->name == 'Publisher'){
            $query->where(['gid' => Yii::$app->user->identity->gid]);
        }
        $query->andWhere(['langs'=>$_lang]);
        $result = $query->all();
        $data[''] = 'เลือกประเภทเมนู';
        foreach ($result as $m) {
            $data[$m->id] = $m->title;
        }

        return $data;
    }
}

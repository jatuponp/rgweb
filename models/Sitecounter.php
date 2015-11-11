<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_sitecounter".
 *
 * @property integer $id
 * @property string $module
 * @property integer $module_id
 * @property string $ip_address
 * @property string $session_id
 * @property string $user_agent
 * @property string $country
 * @property string $datetime
 */
class Sitecounter extends \yii\db\ActiveRecord {

    public $year;
    public $cnt;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_sitecounter';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
//            [['module_id', 'session_id', 'country'], 'required'],
            [['module_id'], 'integer'],
            [['datetime'], 'safe'],
            [['module', 'country'], 'string', 'max' => 100],
            [['ip_address'], 'string', 'max' => 30],
            [['session_id'], 'string', 'max' => 50],
            [['user_agent'], 'string', 'max' => 250]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'module' => 'Module',
            'module_id' => 'Module ID',
            'ip_address' => 'Ip Address',
            'session_id' => 'Session ID',
            'user_agent' => 'User Agent',
            'country' => 'Country',
            'datetime' => 'Datetime',
            'year' => 'เลือกปีที่ต้องการ',
        ];
    }

    public function searchYearBar() {
        if(!$this->year){
            $this->year = 2015;
        }
        $arr = array();
        $data = array();
        for ($i = 1; $i <= 12; $i++) {
            $cnt = Sitecounter::find()
                    ->where(['module' => 'site'])
                    ->andWhere(['MONTH(datetime)' => $i])
                    ->andWhere(['YEAR(datetime)' => $this->year])
                    ->count()
            ;
            $data[] = (int) $cnt;
        }
        $arr[] = array('name' => 'จำนวน', 'data' => $data);

        return $arr;
    }
    
    public function searchDayBar($month,$year) {
        $arr = array();
        $data = array();
        for ($i = 1; $i <= 31; $i++) {
            $cnt = Sitecounter::find()
                    ->where(['module' => 'site'])
                    ->andWhere(['DAY(datetime)' => $i])
                    ->andWhere(['MONTH(datetime)' => $month])
                    ->andWhere(['YEAR(datetime)' => $year])
                    ->count()
            ;
            $data[] = (int) $cnt;
        }
        $arr[] = array('name' => 'จำนวน', 'data' => $data);

        return $arr;
    }

}

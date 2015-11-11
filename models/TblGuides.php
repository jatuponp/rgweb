<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "tbl_guides".
 *
 * @property integer $id
 * @property integer $cid
 * @property string $titles
 * @property string $address
 * @property integer $tampon
 * @property integer $amphur
 * @property string $gps
 * @property integer $distance
 * @property string $fulltexts
 * @property string $contacts
 * @property string $phone
 * @property string $fax
 * @property string $emails
 * @property string $website
 * @property string $langs
 * @property string $applyDate
 */
class TblGuides extends \yii\db\ActiveRecord {

    public $search;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_guides';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['cid', 'titles', 'amphur', 'fulltexts', 'langs'], 'required'],
            [['cid', 'tampon', 'amphur', 'distance'], 'integer'],
            [['fulltexts'], 'string'],
            [['applyDate'], 'safe'],
            [['titles', 'address', 'contacts'], 'string', 'max' => 250],
            [['gps', 'phone', 'fax', 'emails'], 'string', 'max' => 100],
            [['website'], 'string', 'max' => 200],
            [['langs'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'cid' => 'หมวดหมู่',
            'titles' => 'ชื่อสถานที่',
            'address' => 'ที่อยู่',
            'tampon' => 'ตำบล',
            'amphur' => 'อำเภอ',
            'gps' => 'Gps',
            'distance' => 'ระยะทาง',
            'fulltexts' => 'เนื้อหา',
            'contacts' => 'ผู้ดูแล ผู้ประสานงาน',
            'phone' => 'หมายเลขโทรศัพท์',
            'fax' => 'โทรสาร',
            'emails' => 'Emails',
            'website' => 'Website',
            'langs' => 'Langs',
            'applyDate' => 'Apply Date',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $now = date('Y-m-d H:i:s');
            if ($this->isNewRecord) {
                $this->published = 0;
                $this->applyDate = $now;
                if (!$this->langs) {
                    $this->langs = 'thai';
                }
            }
            return true;
        }
        return false;
    }

    public function search() {
        $search = $this->search;
        $langs = $this->langs;
        $cid = $this->cid;
        $amphur = $this->amphur;
        $query = TblGuides::find();
        if ($search){
            $query->where('titles LIKE :s', [':s' => "%$search%"]);
        }
        if ($langs){
            $query->andWhere(['langs' => $langs]);
        }
        if ($cid){
            $query->andWhere(['cid' => $cid]);
        }
        if ($amphur){
            $query->andWhere(['amphur' => $amphur]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;
    }

    public function getCat($cid = 1) {
        $cat = array('1' => 'Tourist Attraction', '2' => 'Hotel', '3' => 'Restaurant', '4' => 'Souvenir');
        return $cat[$cid];
    }

}

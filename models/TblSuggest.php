<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_suggest".
 *
 * @property integer $id
 * @property string $fullname
 * @property string $email
 * @property string $suggest
 */
class TblSuggest extends \yii\db\ActiveRecord {

    public $verifyCode;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tbl_suggest';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['fullname', 'email', 'suggest', 'test'], 'required'],
            [['suggest'], 'string'],
            [['fullname'], 'string', 'max' => 500],
            [['email'], 'string', 'max' => 200],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'fullname' => 'ชื่อ-นามสกุล (First name - Last name)',
            'email' => 'อีเมลล์ (Email)',
            'suggest' => 'ข้อเสนอแนะ (Suggest)',
            'verifyCode' => 'กรุณากรอกรหัสตรวจสอบให้ถูกต้อง'
        ];
    }

}

<?php

namespace app\modules\admin\controllers;

use app\models\User;
use yii\filters\AccessControl;

\Yii::$app->name = '<i class="glyphicon glyphicon-user"></i> บริหารข้อมูลผู้ใช้';

class UserController extends \yii\web\Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['Administrator'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $model = new User;
        return $this->render('index', ['model' => $model]);
    }

    public function actionUpdate($id = null) {
        $model = new User();
        if ($model->load(\Yii::$app->request->post())) {
            $request = \Yii::$app->getRequest()->post('User');
            $id = $request['id'];
            if ($id) {
                $model = User::findOne($id);
            }
            $model->firstName = $request['firstName'];
            $model->lastName = $request['lastName'];
            $model->username = $request['username'];
            $model->email = $request['email'];
            if ($request['password_hash']) {
                $model->scenario = 'default';
                $model->setPassword($request['password_hash']);
            } else {
                $model->scenario = 'update';
            }
            $model->generateAuthKey();
            if ($model->save()) {

                $r = new \yii\rbac\DbManager();
                $role = new \yii\rbac\Role();
                $role->name = $request['authType'];
                if (!$id) {
                    $r->assign($role, $model->id);
                } else {
                    if ($request['authType']) {
                        $r->revokeAll($model->id);
                        $r->assign($role, $model->id);
                    }
                }
                $this->redirect(['user/index']);
            } else {
                print_r($model->getErrors());
                exit();
            }
        }

        if ($id) {
            $model = User::find()->where(['id' => $id])->one();
            $model->scenario = 'update';
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id) {
        $model = User::findOne($id);
        if ($model->delete()) {
            $r = new \yii\rbac\DbManager();
            $r->revokeAll($model->id);
            $this->redirect(['user/index']);
        }
    }

}

<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\User;

class UserController extends Controller
{
    /**
     * @return array
     */
    public function actionLogin()
    {
        $email = Yii::$app->request->post('email');
        $password = Yii::$app->request->post('password');
        $user = User::findOne(['email' => $email]);
        if ($user && $user->validatePassword($password)) {
            return ['auth_key' => $user->auth_key];
        }
        return ['status' => 'error', 'message' => 'Invalid email or password'];
    }
}
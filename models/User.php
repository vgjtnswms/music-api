<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['email', 'password_hash'], 'required'],
            ['email', 'email'],
            ['email', 'unique'],
            ['password_hash', 'string', 'min' => 6],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * @param string $password
     * @return void
     */
    public function setPassword($password)
    {
        $this->password_hash = \Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    /**
     * @return void
     */
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->getSecurity()->generateRandomString();
    }

    /**
     * @param string $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return \Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }
}
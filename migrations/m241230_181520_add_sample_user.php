<?php

use yii\db\Migration;

/**
 * Class m241230_181520_add_sample_user
 */
class m241230_181520_add_sample_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('users', [
            'email' => 'user@example.com',
            'password_hash' => Yii::$app->security->generatePasswordHash('password123'),
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'created_at' => new \yii\db\Expression('NOW()'),
            'updated_at' => new \yii\db\Expression('NOW()'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('users', ['email' => 'user@example.com']);
    }
}

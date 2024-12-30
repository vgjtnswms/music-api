<?php

namespace app\models;

use app\models\enums\PostStatusEnum;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

class Post extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['title', 'status', 'author_id'], 'required'],
            [['description'], 'string'],
            [['status'], 'in', 'range' => PostStatusEnum::values()],
            [['author_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['audio_file'], 'file', 'extensions' => 'mp3', 'maxSize' => 10 * 1024 * 1024], // Макс. 10 МБ
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])
            ->viaTable('post_tag', ['post_id' => 'id']);
    }
}
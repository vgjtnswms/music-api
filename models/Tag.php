<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

class Tag extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return 'tags';
    }

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            ['name', 'string', 'max' => 255],
            ['name', 'unique'],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::class, ['id' => 'post_id'])
            ->viaTable('post_tag', ['tag_id' => 'id']);
    }
}
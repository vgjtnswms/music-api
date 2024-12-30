<?php

namespace app\controllers;

use app\models\enums\PostStatusEnum;
use app\models\Post;
use app\models\Tag;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

class PostController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    /**
     * @return array
     */
    public function actionIndex()
    {
        // TODO: отсутсвует валидация параметров
        $tags = Yii::$app->request->get('tags');
        $query = Post::find()->where(['status' => PostStatusEnum::ACTIVE->value]);
        if ($tags) {
            $query->joinWith('tags')->andWhere(['tag.name' => explode(',', $tags)]);
        }
        return $query->all();
    }

    /**
     * @return Post|array
     */
    public function actionCreate()
    {
        $post = new Post();
        $post->load(Yii::$app->request->post(), '');
        $post->author_id = Yii::$app->user->id;

        $file = \yii\web\UploadedFile::getInstanceByName('audio_file');
        if ($file) {
            $filePath = 'uploads/' . uniqid() . '.' . $file->extension;
            if ($file->saveAs($filePath)) {
                $post->audio_file = $filePath;
            } else {
                return ['status' => 'error', 'message' => 'Failed to upload file'];
            }
        }

        if ($post->validate() && $post->save()) {
            $tags = Yii::$app->request->post('tags', []);
            foreach ($tags as $tagName) {
                $tag = Tag::findOne(['name' => $tagName]) ?? new Tag(['name' => $tagName]);
                if ($tag->save()) {
                    $post->link('tags', $tag);
                }
            }
            return $post;
        }
        return ['status' => 'error', 'errors' => $post->errors];
    }

    /**
     * @param mixed $id
     * @return Post|array
     * @throws ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        $post = Post::findOne($id);
        if (!$post || $post->author_id != Yii::$app->user->id) {
            throw new ForbiddenHttpException('Access denied');
        }
        $post->load(Yii::$app->request->post(), '');
        if ($post->validate() && $post->save()) {
            return $post;
        }
        return ['status' => 'error', 'errors' => $post->errors];
    }

    /**
     * @param mixed $id
     * @return array
     * @throws ForbiddenHttpException
     */
    public function actionDelete($id)
    {
        $post = Post::findOne($id);
        if (!$post || $post->author_id != Yii::$app->user->id) {
            throw new ForbiddenHttpException('Access denied');
        }
        $post->delete();
        return ['status' => 'success'];
    }

    /**
     * @return mixed
     */
    public function actionError()
    {
        if (($exception = Yii::$app->errorHandler->exception) !== null) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'error' => true,
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ];
        }
    }
}
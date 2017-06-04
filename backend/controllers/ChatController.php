<?php

namespace backend\controllers;

use Yii;
use backend\models\Chat;
use backend\models\ChatSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\User;

/**
 * ChatController implements the CRUD actions for Chat model.
 */
class ChatController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException('Access denied');
        }
        return parent::beforeAction($action);
    }

    /**
     * Lists all Chat models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->layout = false;
		
        if (true || Yii::$app->request->isPost) {
            $id = Yii::$app->request->post('id');
            $scroll = Yii::$app->request->post('scroll');

            if ( ! $user = User::findOne(['id' => $id, 'status' => '1'])) throw new NotFoundHttpException('User not found.');

            $unread = Chat::unreadMessagesBySenderId($id);
            if ($unread) {
				Chat::removeUnreadMessageBySenderId($id);
				$scroll = true;
			}

            $message = $this->render('index',[
						'messages' => Chat::loadConversation($id, 50),
						'user' => $user
					]);

            //echo $message;
            //return Json::encode(array('id'=>$id));


            return Json::encode(array(
                'id'=>$id,
                'scroll' => (bool)$scroll,
                'unread' => $unread,
                'messages' => $message
            ));
        }
    }

    /**
     * Creates a new Chat model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->request->isPost) {
            $id = Yii::$app->request->post('id');
            $text = Yii::$app->request->post('text');
            $scroll = Yii::$app->request->post('scroll');
            
            $user = User::findOne(['id' => $id, 'status' => 1]);
            if (!$user) throw new NotFoundHttpException('User not found.');
            
            $error = null;
            $response = Chat::createMessage($id, $text);
            if (!$response) {
                $error = Yii::t('app','Failed to save message');
            } else if (is_array($response)) {
                $error = array_shift($response);
                if (is_array($response)) $error = array_shift($error);
            }

            $this->layout = false;
            
            Chat::removeUnreadMessageBySenderId($id);
            $messages = $this->render('index', [
                    'messages' => Chat::loadConversation($id, 50),
                    'user' => $user
                ]);
            
            return Json::encode(array(
                'id' => $id,
                'error' => $error,
                'scroll' => (bool)$scroll,
                'messages' => $messages
            ));
        }
    }

    public function actionInform()
    {
        if (Yii::$app->request->isPost) {
            $this->layout = false;
            return Json::encode(array(
				'unread' => Chat::unreadMessages()
            ));
        }
    }

    /**
     * Deletes an existing Chat model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Chat model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Chat the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Chat::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

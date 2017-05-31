<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use app\models\Message;
use app\models\User;

class MessageController extends \yii\rest\Controller
{

    public function init()
    {
        parent::init();
        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    public function actionTest()
    {
        $message = [];
    }

    public function actionSendMessage()
    {
        $request = json_decode(file_get_contents('php://input'), true);

        $author = User::find()->where(['auth_key' => $request['auth_key']])->one();

        if (!$author || $author->id != $request['user_id']) {
            return ['success' => false, 'message' => 'invalid auth key'];
        }

        $messageData = [];
        $messageData['user_id'] = $request['user_id'];
        $messageData['conversation_id'] = $request['conversation_id'];
        $messageData['participants'] = $request['participants'];
        $messageData['message'] = $request['message'];
        //mt_rand as id generator - remember, this is just a quick prototype¯\_(ツ)_/¯
        $messageData['message_id'] = mt_rand(10000000,99999999);
        $messageData['operation'] = "newMessage";
        $messageData['timestamp'] = date("Y-m-d H:i:s");

        foreach($request['participants'] as $participant){
            Message::addMessage($participant, $messageData);
        }

        return ['success' => true];
    }

    public function actionTransferOperativeToPersistent()
    {
        $request = json_decode(file_get_contents('php://input'), true);

        $user = User::find()->where(['auth_key' => $request['auth_key']])->one();

        if (!$user || $user->id != $request['user_id']) {
            return ['success' => false, 'message' => 'invalid auth key'];
        }

        Message::transferOperativeToPersistent($request['user_id']);
        return ['success' => true];
    }

    public function actionGetConversation()
    {
        $request = json_decode(file_get_contents('php://input'), true);

        $user = User::find()->where(['auth_key' => $request['auth_key']])->one();

        if (!$user || $user->id != $request['user_id']) {
            return ['success' => false, 'message' => 'invalid auth key'];
        }

        $result = Message::getUserConversation($request['user_id'], $request['conversation_id']);
        return ['success' => true, 'conversation' => $result];
    }
}

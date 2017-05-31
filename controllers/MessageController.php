<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use app\models\Message;

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

    public function actionNewMessage()
    {
        $request = json_decode(file_get_contents('php://input'), true);

        //mt_rand as id generator - remember, this is just a quick prototype¯\_(ツ)_/¯
        $request['message_id'] = mt_rand(10000000,99999999);
        $request['operation'] = "newMessage";
        $request['timestamp'] = date("Y-m-d H:i:s");

        foreach($request['participants'] as $participant){
            Message::addMessage($participant, $request);
        }

        return ['success' => true];
    }
}

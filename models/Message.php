<?php
namespace app\models;

use Yii;
use app\models\User;

/**
 * Message model
 */
class Message extends \yii\base\Model
{

    /**
     * Returns existing data from operative layer for user
     */
    public static function getExistingOperativeData($userId, $operativeShardId)
    {
        $sql = "
        SELECT * FROM technodb.operative_:shard_id
        WHERE user_id = :user_id
        ";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':user_id', $userId);
        $command->bindValue(':shard_id', $operativeShardId);
        return $command->queryAll();
    }

    /**
     * Adds new message to operative layer for certain user
     */
    public static function addMessage($userId, $data)
    {
        $operativeShardId = User::calculateOperativeShardId($userId);

        $commandResult = self::getExistingOperativeData($userId, $operativeShardId);

        $existingOperativeData = [];
        $noExistingData = true;
        if (isset($commandResult[0]['data'])) {
            $noExistingData = false;
            $existingOperativeData = json_decode($commandResult[0]['data'], true);
        }

        if (!empty($existingOperativeData)) {
            array_unshift($existingOperativeData, $data);
        } else {
            $existingOperativeData[] = $data;
        }

        $transaction = Yii::$app->db->beginTransaction();
        if ($noExistingData) {
            $sql = "
            INSERT INTO technodb.operative_:shard_id (user_id, data)
            VALUES(:user_id, :data)
            ";
        } else {
            $sql = "
            UPDATE operative_:shard_id
            SET data = :data
            WHERE user_id = :user_id
            ";
        }

        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':user_id', $userId);
        $command->bindValue(':shard_id', $operativeShardId);
        $command->bindValue(':data', json_encode($existingOperativeData));
        $commandResult = $command->execute();
        $transaction->commit();

        return true;
    }

    /**
     * Moves data from user operative layer to persistent layer
     */
    public static function transferOperativeToPersistent($userId)
    {
        $operativeShardId = User::calculateOperativeShardId($userId);
        $persistentShardId = User::calculatePersistentShardId($userId);

        $operativeDataCommandResult = self::getExistingOperativeData($userId, $operativeShardId);
        $operativeData = [];
        if (isset($operativeDataCommandResult[0]['data'])) {
            $operativeData = json_decode($operativeDataCommandResult[0]['data'], true);
            foreach($operativeData as $operation){
                //echo "<pre>";
                //\yii\helpers\VarDumper::dump($operation);exit;
                if ($operation['operation'] == "newMessage") {
                    $messageData = [];
                    $messageData['author_id'] = $operation['author_id'];
                    $messageData['message_id'] = $operation['message_id'];
                    $messageData['message'] = $operation['message'];
                    $messageData['timestamp'] = $operation['timestamp'];

                    $sql = "
                    SELECT * FROM technodb.persistent_:shard_id
                    WHERE user_id = :user_id AND conversation_id = :conversation_id
                    ";
                    $command = Yii::$app->db->createCommand($sql);
                    $command->bindValue(':user_id', $userId);
                    $command->bindValue(':conversation_id', $operation['conversation_id']);
                    $command->bindValue(':shard_id', $persistentShardId);
                    $existingConversationDataCommandResult = $command->queryAll();
                    $existingConversationData = [];
                    $noExistingData = true;
                    if (isset($existingConversationDataCommandResult[0]['data'])) {
                        $noExistingData = false;
                        $existingConversationData = json_decode($existingConversationDataCommandResult[0]['data'], true);
                    }

                    if (!empty($existingConversationData)) {
                        array_unshift($existingConversationData["messages"], $messageData);
                    } else {
                        $existingConversationData["participants"] = $operation["participants"];
                        $existingConversationData["messages"][] = $messageData;
                    }

                    $transaction = Yii::$app->db->beginTransaction();
                    if ($noExistingData) {
                        $sql = "
                        INSERT INTO technodb.persistent_:shard_id (user_id, conversation_id, data)
                        VALUES(:user_id, :conversation_id, :data)
                        ";
                    } else {
                        $sql = "
                        UPDATE technodb.persistent_:shard_id
                        SET data = :data
                        WHERE user_id = :user_id AND conversation_id = :conversation_id
                        ";
                    }

                    $command = Yii::$app->db->createCommand($sql);
                    $command->bindValue(':user_id', $userId);
                    $command->bindValue(':shard_id', $persistentShardId);
                    $command->bindValue(':conversation_id', $operation['conversation_id']);
                    $command->bindValue(':data', json_encode($existingConversationData));
                    $commandResult = $command->execute();
                    $transaction->commit();
                }
            }
            $transaction = Yii::$app->db->beginTransaction();
            $sql = "
            DELETE FROM operative_:shard_id
            WHERE user_id = :user_id
            ";

            $command = Yii::$app->db->createCommand($sql);
            $command->bindValue(':user_id', $userId);
            $command->bindValue(':shard_id', $operativeShardId);
            $commandResult = $command->execute();
            $transaction->commit();
        }
    }
}

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
     * Adds new message to operative layer for certain user
     */
    public static function addMessage($user, $data)
    {
        $operativeShardId = User::calculateOperativeShardId($user);
        $sql = "
        SELECT * FROM technodb.operative_:shard_id
        WHERE user_id = :user_id
        ";
        $command = Yii::$app->db->createCommand($sql);
        $command->bindValue(':user_id', $user);
        $command->bindValue(':shard_id', $operativeShardId);
        $commandResult = $command->queryAll();

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
        $command->bindValue(':user_id', $user);
        $command->bindValue(':shard_id', $operativeShardId);
        $command->bindValue(':data', json_encode($existingOperativeData));
        $commandResult = $command->execute();
        $transaction->commit();

        return true;
    }
}

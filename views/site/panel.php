<?php

/* @var $this yii\web\View */

?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2><?= Yii::$app->user->identity->name ?> [id: <?= Yii::$app->user->identity->id ?>]</h2>

                <p><b>Your auth key:</b> <?= Yii::$app->user->identity->auth_key ?></p>

                <p><b>Operative shard ID:</b> <?= $operativeShardId ?></p>
                <p><b>Persistent shard ID:</b> <?= $persistentShardId ?></p>
            </div>
            <div class="col-lg-4 col-lg-offset-4">
              <ul>
                <h2>Message any of them</h2>
                <?php foreach($allUsers as $user) { ?>
                    <li><?= $user->name ?> [id: <?= $user->id ?>]</li>
                <?php } ?>
              </ul>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-12">
                <h2>API</h2>
                <?php $conversationExampleId = mt_rand(0, 100);?>
                <b><i>Send Mesaage</b></i><br>
                <b>url:</b> https://technodb.mburmistrov.ru/message/send-message<br>
                <b>example:</b> {"user_id": <?= Yii::$app->user->identity->id ?>, "conversation_id": <?= $conversationExampleId ?>, "participants": [<?= Yii::$app->user->identity->id ?>, 2], "message": "Hello 2-nd user!", "auth_key": "<?= Yii::$app->user->identity->auth_key ?>"}
                <hr>
                <b><i>Get Conversation</b></i><br>
                <b>url:</b> https://technodb.mburmistrov.ru/message/get-conversation<br>
                <b>return:</b> conversation data and messages from both persistent and operative layers<br>
                <b>example:</b> {"user_id": <?= Yii::$app->user->identity->id ?>, "conversation_id": <?= $conversationExampleId ?>,  "auth_key": "<?= Yii::$app->user->identity->auth_key ?>"}
                <hr>
                <b><i>Transfer data from operative to persistent layer</b></i><br>
                <b>url:</b> https://technodb.mburmistrov.ru/message/transfer-operative-to-persistent<br>
                <b>example:</b> {"user_id": <?= Yii::$app->user->identity->id ?>, "auth_key": "<?= Yii::$app->user->identity->auth_key ?>"}
                <hr>
            </div>

        </div>
    </div>
</div>

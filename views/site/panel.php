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

    </div>
</div>

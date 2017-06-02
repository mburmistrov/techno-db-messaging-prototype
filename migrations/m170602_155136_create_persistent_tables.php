<?php

use yii\db\Migration;

class m170602_155136_create_persistent_tables extends Migration
{
    public function up()
    {
        $this->createTable('persistent_1', [
          'id' => $this->primaryKey(),
          'user_id' => $this->integer()->notNull(),
          'conversation_id' => $this->integer()->notNull(),
          'data' => $this->text()->notNull(),
        ]);

        $this->createTable('persistent_2', [
          'id' => $this->primaryKey(),
          'user_id' => $this->integer()->notNull(),
          'conversation_id' => $this->integer()->notNull(),
          'data' => $this->text()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('persistent_1');

        $this->dropTable('persistent_2');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}

<?php

use yii\db\Migration;

class m170602_154516_create_operative_tables extends Migration
{
    public function up()
    {
        $this->createTable('operative_1', [
          'id' => $this->primaryKey(),
          'user_id' => $this->integer()->notNull(),
          'data' => $this->text()->notNull(),
        ]);

        $this->createTable('operative_2', [
          'id' => $this->primaryKey(),
          'user_id' => $this->integer()->notNull(),
          'data' => $this->text()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('operative_1');

        $this->dropTable('operative_2');
    }
}

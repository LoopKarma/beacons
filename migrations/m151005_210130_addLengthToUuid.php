<?php

use yii\db\Schema;
use yii\db\Migration;

class m151005_210130_addLengthToUuid extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%merchant}}', 'uuid', $this->string(36));
    }

    public function down()
    {
        return true;
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

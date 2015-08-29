<?php

use yii\db\Schema;
use yii\db\Migration;

class m150829_175838_merchantNameLengthFix extends Migration
{
    public $tableName = '{{%merchant}}';

    public function up()
    {
        $this->alterColumn($this->tableName, 'name', $this->string(100)->notNull());
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

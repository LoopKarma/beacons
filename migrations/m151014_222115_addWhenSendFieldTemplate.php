<?php

use yii\db\Schema;
use yii\db\Migration;

class m151014_222115_addWhenSendFieldTemplate extends Migration
{
    public $tableName = '{{%coupon_template}}';

    public function up()
    {
        $this->addColumn($this->tableName, 'send_scenario', $this->smallInteger(1)->defaultValue(1));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'send_scenario');
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

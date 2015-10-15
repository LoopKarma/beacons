<?php

use yii\db\Schema;
use yii\db\Migration;

class m151015_210127_couponLongerUuid extends Migration
{
    public $tableName = '{{%coupon}}';

    public function up()
    {
        $this->alterColumn($this->tableName, 'uuid', $this->string(36)->notNull());
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

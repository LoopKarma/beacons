<?php

use yii\db\Schema;
use yii\db\Migration;

class m151122_183718_addLabelColorTemplate extends Migration
{
    public $tableName = '{{%coupon_template}}';

    public function up()
    {
        $this->addColumn($this->tableName, 'labelColor', $this->string(16)->defaultValue(null));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'labelColor');
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

<?php

use yii\db\Schema;
use yii\db\Migration;

class m150901_203707_removeColorAttrFromTemplate extends Migration
{
    public $tableName = '{{%coupon_template}}';

    public function up()
    {
        $this->dropColumn($this->tableName, 'label_color');
        $this->dropColumn($this->tableName, 'foreground_color');
        $this->dropColumn($this->tableName, 'background_color');
    }

    public function down()
    {
        $this->addColumn($this->tableName, 'foreground_color', $this->string(30)->defaultValue(null));
        $this->addColumn($this->tableName, 'background_color', $this->string(30)->defaultValue(null));
        $this->addColumn($this->tableName, 'label_color', $this->string(30)->defaultValue(null));
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

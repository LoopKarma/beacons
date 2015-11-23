<?php

use yii\db\Schema;
use yii\db\Migration;

class m151122_183718_addLabelColorTemplate extends Migration
{
    public $tableName = '{{%coupon_template}}';

    public function up()
    {
        $this->addColumn(
            $this->tableName,
            'label_color',
            $this->string(16)->defaultValue(null) . ' AFTER foreground_color'
        );
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'label_color');
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

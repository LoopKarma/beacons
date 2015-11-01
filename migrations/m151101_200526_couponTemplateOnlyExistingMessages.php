<?php

use yii\db\Schema;
use yii\db\Migration;

class m151101_200526_couponTemplateOnlyExistingMessages extends Migration
{
    public $tableName = '{{%coupon_template}}';

    public function up()
    {
        $this->addColumn(
            $this->tableName,
            'do_not_generate_messages',
            $this->smallInteger()->defaultValue(1) . ' AFTER show_serial'
        );
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'do_not_generate_messages');
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

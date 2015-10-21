<?php

use yii\db\Schema;
use yii\db\Migration;

class m151021_221820_addChDateCoupon extends Migration
{
    public $tableName = '{{%coupon}}';

    public function up()
    {
        $this->addColumn(
            $this->tableName,
            'change_date',
            $this->dateTime() . ' AFTER create_date'
        );
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'change_date');
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

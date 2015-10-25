<?php

use yii\db\Schema;
use yii\db\Migration;

class m151025_204704_addAltText extends Migration
{
    public $tableName = '{{%coupon_template}}';

    public function up()
    {
        $this->addColumn(
            $this->tableName,
            'show_serial',
            $this->smallInteger()->defaultValue(0) . ' AFTER without_barcode'
        );
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'show_serial');
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

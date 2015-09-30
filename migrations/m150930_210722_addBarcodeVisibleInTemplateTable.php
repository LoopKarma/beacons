<?php

use yii\db\Schema;
use yii\db\Migration;

class m150930_210722_addBarcodeVisibleInTemplateTable extends Migration
{
    public $tableName = '{{%coupon_template}}';

    public function up()
    {
        $this->addColumn(
            $this->tableName,
            'barcode_visible',
            $this->smallInteger(1)->defaultValue(1). ' AFTER without_barcode'
        );
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

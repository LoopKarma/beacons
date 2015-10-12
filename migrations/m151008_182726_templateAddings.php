<?php

use yii\db\Schema;
use yii\db\Migration;

class m151008_182726_templateAddings extends Migration
{
    public $tableName = '{{%coupon_template}}';

    public function up()
    {

        $this->addColumn(
            $this->tableName,
            'send_unlimited',
            $this->smallInteger(1)->defaultValue(0) . ' AFTER barcode_message_encoding'
        );

        $this->addColumn(
            $this->tableName,
            'icon',
            $this->string(10)->defaultValue(null) . ' AFTER send_unlimited'
        );
        $this->addColumn($this->tableName, 'icon2x', $this->string(10)->defaultValue(null) . ' AFTER icon');
        $this->addColumn($this->tableName, 'icon3x', $this->string(10)->defaultValue(null) . ' AFTER icon2x');

        $this->addColumn($this->tableName, 'logo', $this->string(10)->defaultValue(null) . ' AFTER icon3x');
        $this->addColumn($this->tableName, 'logo2x', $this->string(10)->defaultValue(null) . ' AFTER logo');
        $this->addColumn($this->tableName, 'logo3x', $this->string(10)->defaultValue(null) . ' AFTER logo2x');

        $this->addColumn($this->tableName, 'strip', $this->string(10)->defaultValue(null) . '  AFTER logo3x');
        $this->addColumn($this->tableName, 'strip2x', $this->string(10)->defaultValue(null) . ' AFTER strip');
        $this->addColumn($this->tableName, 'strip3x', $this->string(10)->defaultValue(null) . ' AFTER strip2x');


        $this->addColumn($this->tableName, 'name', $this->string(50)->notNull() . ' AFTER template_id');

        /*
        $this->dropColumn($this->tableName, 'icon_retina');
        $this->dropColumn($this->tableName, 'logo_retina');
        $this->dropColumn($this->tableName, 'strip_image');
        $this->dropColumn($this->tableName, 'strip_image_retina');
        $this->dropColumn($this->tableName, 'barcode_visible');
        */
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

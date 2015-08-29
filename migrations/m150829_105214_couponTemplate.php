<?php

use yii\db\Schema;
use yii\db\Migration;

class m150829_105214_couponTemplate extends Migration
{
    public $tableName = '{{%coupon_template}}';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable($this->tableName, [
            'template_id' => $this->primaryKey(5),
            'active' => $this->smallInteger(1)->defaultValue(1)->notNull(),
            'merchant_id' => $this->integer(10)->notNull(),
            'pos' => $this->integer(10),
            'organization_name' => $this->string(256)->defaultValue("GetCoupon"),
            'team_identifier' => $this->string(256)->defaultValue("8V4MJ9GE5G"),
            'logo_text' => $this->string(256)->defaultValue(null),
            'description' => $this->string(256)->defaultValue(null),
            'foreground_color' => $this->string(30)->defaultValue(null),
            'background_color' => $this->string(30)->defaultValue(null),
            'label_color' => $this->string(30)->defaultValue(null),
            'coupon_fields' => $this->text()->notNull(),
            'beacon_relevant_text' => $this->string(256)->defaultValue("Воспользуйтесь купоном!"),
            'barcode_format' => $this->string(100)->defaultValue("PKBarcodeFormatQR"),
            'barcode_message_encoding' => $this->string(100)->defaultValue("iso-8859-1")
        ], $tableOptions);
        $this->createIndex('template_merch', $this->tableName, 'merchant_id');
        $this->createIndex('template_pos', $this->tableName, 'pos');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
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

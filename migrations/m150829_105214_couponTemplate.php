<?php

use yii\db\Migration;

class m150829_105214_couponTemplate extends Migration
{
    public $tableName = '{{%coupon_template}}';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable($this->tableName, [
            'template_id' => $this->primaryKey(5),
            'create_date' => $this->dateTime()->notNull(),
            'update_date' => $this->dateTime(),
            'active' => $this->smallInteger(1)->defaultValue(1)->notNull(),
            'merchant_id' => $this->integer(10)->notNull(),
            'coupon' => $this->text()->notNull(),
            'background_color' => $this->string(16)->defaultValue(null),
            'foreground_color' => $this->string(16)->defaultValue(null),
            'organization_name' => $this->string(256)->defaultValue(null),
            'team_identifier' => $this->string(256)->defaultValue(null),
            'logo_text' => $this->string(256)->defaultValue(null),
            'description' => $this->string(256)->defaultValue(null),
            'without_barcode' => $this->smallInteger(1)->defaultValue(null),
            'beacon_relevant_text' => $this->string(256)->defaultValue(null),
            'barcode_format' => $this->string(100)->defaultValue(null),
            'barcode_message_encoding' => $this->string(100)->defaultValue(null),
            'icon' => $this->string(10)->defaultValue(null),
            'icon_retina' => $this->string(10)->defaultValue(null),
            'logo' => $this->string(10)->defaultValue(null),
            'logo_retina' => $this->string(10)->defaultValue(null),
            'strip_image' => $this->string(10)->defaultValue(null),
            'strip_image_retina' => $this->string(10)->defaultValue(null),
        ], $tableOptions);
        $this->createIndex('template_merch', $this->tableName, 'merchant_id');
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

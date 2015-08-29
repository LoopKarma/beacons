<?php

use yii\db\Schema;
use yii\db\Migration;

class m150829_104754_barcodeMessage extends Migration
{
    public $tableName = '{{%barcode_message}}';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable($this->tableName, [
            'message_id' => $this->primaryKey(10),
            'create_date' => $this->dateTime()->notNull(),
            'update_date' => $this->dateTime(),
            'merchant_id' => $this->integer(10)->notNull(),
            'message' => $this->text(),
            'utilize' => $this->smallInteger(1)->defaultValue(0),
        ], $tableOptions);

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

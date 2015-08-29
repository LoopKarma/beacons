<?php

use yii\db\Schema;
use yii\db\Migration;

class m150829_115224_couponTable extends Migration
{
    public $tableName = '{{%coupon}}';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable($this->tableName, [
            'coupon_id' => $this->primaryKey(10),
            'create_date' => $this->dateTime()->notNull(),
            'update_date' => $this->dateTime(),
            'template_id' => $this->integer(10)->notNull(),
            'merchant_id' => $this->integer(10)->notNull(),
            'pos_id' => $this->integer(10)->notNull(),
            'client' => $this->string(100)->notNull(),
            'confirmed' => $this->smallInteger(1)->defaultValue(0)->notNull(),
            'message_id' => $this->integer(10)->notNull(),
            'uuid' => $this->string(32)->notNull(),
            'major' => $this->string(32)->notNull(),
            'minor' => $this->string(32)->notNull(),
            'serial_number' => $this->string(100)->notNull()->unique(),
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

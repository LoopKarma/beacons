<?php

use yii\db\Migration;

class m150829_094141_addPosTable extends Migration
{
    public $tableName = "{{%pos}}";


    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable($this->tableName, [
            'pos_id' => $this->integer(10)->notNull(),
            'merchant_id' => $this->integer(10)->notNull(),
            'create_date' => $this->dateTime()->notNull(),
            'update_date' => $this->dateTime(),
            'address' => $this->string(256),
            'beacon_identifier' => $this->string(256),
            'major' => $this->string(20),
            'minor' => $this->string(20),
        ], $tableOptions);
        $this->addPrimaryKey('pos_id', $this->tableName, 'pos_id');
        $this->createIndex('pos_major', $this->tableName, 'major');
        $this->createIndex('pos_minor', $this->tableName, 'minor');
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

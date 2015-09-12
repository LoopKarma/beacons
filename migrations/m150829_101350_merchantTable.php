<?php

use yii\db\Migration;

class m150829_101350_merchantTable extends Migration
{
    public $tableName = '{{%merchant}}';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable($this->tableName, [
            'merchant_id' => $this->primaryKey(5),
            'create_date' => $this->dateTime()->notNull(),
            'update_date' => $this->dateTime(),
            'uuid' => $this->string(32)->notNull(),
            'name' => $this->string(100)->notNull(),
            'description' => $this->string(256)->defaultValue(null),
            'pass_type_id' => $this->string(256)->notNull(),
            'cert_files' => $this->text(),
        ], $tableOptions);
        $this->createIndex('merchant_uuid', $this->tableName, 'uuid');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}

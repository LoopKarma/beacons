<?php

use yii\db\Schema;
use yii\db\Migration;

class m150916_212604_files extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable(
            '{{%file}}',
            [
                'file_id' => $this->primaryKey(10),
                'name' =>  $this->string(50)->notNull()->defaultValue($this->dateTime()),
                'original_name' => $this->string(150),
                'path' => $this->string(100)->notNull(),
            ],
            $tableOptions
        );
    }

    public function down()
    {
        $this->dropTable('{{%file}}');
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

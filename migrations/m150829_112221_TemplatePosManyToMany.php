<?php

use yii\db\Schema;
use yii\db\Migration;

class m150829_112221_TemplatePosManyToMany extends Migration
{
    public $tableName = '{{%template_pos}}';
    public $tempalteTableName = '{{%coupon_template}}';
    public $posTableName = '{{%pos}}';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable($this->tableName, [
            'template_id' => $this->integer(10)->notNull(),
            'pos_id' => $this->integer(10)->notNull(),
        ], $tableOptions);
        $this->createIndex('tp_template', $this->tableName, ['template_id', 'pos_id']);
        $this->createIndex('tp_pos', $this->tableName, ['pos_id', 'template_id']);

        $this->addForeignKey(
            'fk_template',
            $this->tableName,
            'template_id',
            $this->tempalteTableName,
            'template_id',
            'NO ACTION',
            'NO ACTION'
        );
        $this->addForeignKey(
            'fk_pos',
            $this->tableName,
            'pos_id',
            $this->posTableName,
            'pos_id',
            'NO ACTION',
            'NO ACTION'
        );
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

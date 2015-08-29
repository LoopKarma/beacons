<?php

use yii\db\Schema;
use yii\db\Migration;

class m150829_102528_userTable extends Migration
{
    public $tableName = '{{%user}}';

    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable($this->tableName, [
            'user_id' => $this->primaryKey(10),
            'create_date' => $this->dateTime()->notNull(),
            'update_date' => $this->dateTime(),
            'login' => $this->string(50)->notNull(),
            'email' => $this->string(100)->defaultValue(null),
            'password' => $this->string(256)->notNull(),
            'role' => $this->string(50),
            'auth_key' => $this->string(50)->notNull(),
        ], $tableOptions);

        //add admin user to user table
        $adminUser = [
            'create_date' => date('Y-m-d H:i:s'),
            'update_date' => date('Y-m-d H:i:s'),
            'login' => 'admin',
            'email' => Yii::$app->params['adminEmail'],
            'password' => Yii::$app->security->generatePasswordHash(Yii::$app->params['adminPassword']),
            'role' => 'administrator',
            'auth_key' => Yii::$app->security->generateRandomString(50),
        ];
        $this->insert($this->tableName, $adminUser);
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

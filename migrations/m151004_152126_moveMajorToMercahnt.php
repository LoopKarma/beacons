<?php

use yii\db\Schema;
use yii\db\Migration;

class m151004_152126_moveMajorToMercahnt extends Migration
{
    public function up()
    {
        $this->addColumn('{{%merchant}}', 'major', $this->string(20) . ' AFTER uuid');
        $this->dropColumn('{{%pos}}', 'major');

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

<?php

namespace mtest\files\migrations\testnm;

use yii\db\Migration;

/**
 * Class m190219_201811_test
 */
class m190219_201711_nm extends Migration
{

    const table = "{{%test_table_nm}}";

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::table, [
            'col1' => $this->primaryKey(),
            'col2' => $this->string(25)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::table);

        return true;
    }


}

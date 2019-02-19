<?php

use yii\db\Migration;

/**
 * Class m190219_201811_test
 */
class m190219_201811_test11 extends Migration
{

    const table = "{{%test_table11}}";

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

<?php

use somov\common\traits\CommandRunTrait;
use somov\migration\MigrateController;

/**
 * Created by PhpStorm.
 * User: develop
 * Date: 19.02.2019
 * Time: 21:56
 */
class MigrationControllerTest extends Codeception\TestCase\Test
{

    use CommandRunTrait;

    /**
     * @param $test
     * @param null $extend
     * @return array
     */
    private function getConfig($test, $extend = null)
    {
        $path = Yii::getAlias('@mtest/files/migrations/' . $test);
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);

        $c = [
            'class' => MigrateController::class,
            'migrationPath' => $path,
            'interactive' => false,
            'suffix' => $test,
        ];

        if (empty($extend)) {
            return $c;
        }

        return \yii\helpers\ArrayHelper::merge($c, $extend);
    }

    /**
     * @return array
     */
    private function getStat()
    {

        $r = (new \yii\db\Query())
            ->select('suffix, count(version) as cnt')
            ->from('{{%migration}}')
            ->groupBy('suffix')
            ->all();

        return \yii\helpers\ArrayHelper::map($r, 'suffix', 'cnt');
    }

    public function testMigrateUp()
    {

        Yii::getAlias('@mtest/files/migrations/test1');

        $this->runCommand([
            'class' => MigrateController::class,
            'interactive' => false,
        ], 'fresh');


        $this->runCommand($this->getConfig('test1'));
        $this->runCommand($this->getConfig('test2'));

        $this->runCommand($this->getConfig('nm', [
            'migrationPath' => null,
            'migrationNamespaces' => [
                'mtest\files\migrations\testnm'
            ]
        ]), '');

        $this->assertSame([
            'app' => '1',
            'nm' => '2',
            'test1' => '2',
            'test2' => '2',
        ], $this->getStat());

    }

    public function testMigrateDown()
    {
        $this->runCommand($this->getConfig('test1'), 'down', 1);

        $this->assertSame([
            'app' => '1',
            'nm' => '2',
            'test1' => '1',
            'test2' => '2',
        ], $this->getStat());

        $this->runCommand($this->getConfig('test2'), 'down', [2]);

        $this->assertSame([
            'app' => '1',
            'nm' => '2',
            'test1' => '1',
        ], $this->getStat());


        $this->runCommand($this->getConfig('nm', [
            'migrationPath' => null,
            'migrationNamespaces' => [
                'mtest\files\migrations\testnm'
            ]
        ]), 'down', [4]);


        $this->assertSame([
            'app' => '1',
            'test1' => '1',
        ], $this->getStat());

    }
}
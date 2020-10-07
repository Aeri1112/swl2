<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JediItemsJewelryNpcTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JediItemsJewelryNpcTable Test Case
 */
class JediItemsJewelryNpcTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\JediItemsJewelryNpcTable
     */
    protected $JediItemsJewelryNpc;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.JediItemsJewelryNpc',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('JediItemsJewelryNpc') ? [] : ['className' => JediItemsJewelryNpcTable::class];
        $this->JediItemsJewelryNpc = TableRegistry::getTableLocator()->get('JediItemsJewelryNpc', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->JediItemsJewelryNpc);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

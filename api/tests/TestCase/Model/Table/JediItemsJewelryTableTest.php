<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JediItemsJewelryTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JediItemsJewelryTable Test Case
 */
class JediItemsJewelryTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\JediItemsJewelryTable
     */
    protected $JediItemsJewelry;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.JediItemsJewelry',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('JediItemsJewelry') ? [] : ['className' => JediItemsJewelryTable::class];
        $this->JediItemsJewelry = TableRegistry::getTableLocator()->get('JediItemsJewelry', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->JediItemsJewelry);

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

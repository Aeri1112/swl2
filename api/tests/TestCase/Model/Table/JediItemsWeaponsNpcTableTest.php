<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JediItemsWeaponsNpcTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JediItemsWeaponsNpcTable Test Case
 */
class JediItemsWeaponsNpcTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\JediItemsWeaponsNpcTable
     */
    protected $JediItemsWeaponsNpc;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.JediItemsWeaponsNpc',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('JediItemsWeaponsNpc') ? [] : ['className' => JediItemsWeaponsNpcTable::class];
        $this->JediItemsWeaponsNpc = TableRegistry::getTableLocator()->get('JediItemsWeaponsNpc', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->JediItemsWeaponsNpc);

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

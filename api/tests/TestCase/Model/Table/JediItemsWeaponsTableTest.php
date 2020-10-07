<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JediItemsWeaponsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JediItemsWeaponsTable Test Case
 */
class JediItemsWeaponsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\JediItemsWeaponsTable
     */
    protected $JediItemsWeapons;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.JediItemsWeapons',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('JediItemsWeapons') ? [] : ['className' => JediItemsWeaponsTable::class];
        $this->JediItemsWeapons = TableRegistry::getTableLocator()->get('JediItemsWeapons', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->JediItemsWeapons);

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

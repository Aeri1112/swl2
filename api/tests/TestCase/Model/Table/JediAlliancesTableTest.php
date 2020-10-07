<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JediAlliancesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JediAlliancesTable Test Case
 */
class JediAlliancesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\JediAlliancesTable
     */
    protected $JediAlliances;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.JediAlliances',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('JediAlliances') ? [] : ['className' => JediAlliancesTable::class];
        $this->JediAlliances = TableRegistry::getTableLocator()->get('JediAlliances', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->JediAlliances);

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

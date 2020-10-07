<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JediFightLocksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JediFightLocksTable Test Case
 */
class JediFightLocksTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\JediFightLocksTable
     */
    protected $JediFightLocks;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.JediFightLocks',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('JediFightLocks') ? [] : ['className' => JediFightLocksTable::class];
        $this->JediFightLocks = TableRegistry::getTableLocator()->get('JediFightLocks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->JediFightLocks);

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

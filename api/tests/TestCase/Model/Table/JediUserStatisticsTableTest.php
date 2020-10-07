<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JediUserStatisticsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JediUserStatisticsTable Test Case
 */
class JediUserStatisticsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\JediUserStatisticsTable
     */
    protected $JediUserStatistics;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.JediUserStatistics',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('JediUserStatistics') ? [] : ['className' => JediUserStatisticsTable::class];
        $this->JediUserStatistics = TableRegistry::getTableLocator()->get('JediUserStatistics', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->JediUserStatistics);

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

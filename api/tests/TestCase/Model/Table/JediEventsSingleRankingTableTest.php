<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JediEventsSingleRankingTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JediEventsSingleRankingTable Test Case
 */
class JediEventsSingleRankingTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\JediEventsSingleRankingTable
     */
    protected $JediEventsSingleRanking;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.JediEventsSingleRanking',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('JediEventsSingleRanking') ? [] : ['className' => JediEventsSingleRankingTable::class];
        $this->JediEventsSingleRanking = TableRegistry::getTableLocator()->get('JediEventsSingleRanking', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->JediEventsSingleRanking);

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

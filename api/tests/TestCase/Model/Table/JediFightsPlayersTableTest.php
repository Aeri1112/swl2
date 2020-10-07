<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JediFightsPlayersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JediFightsPlayersTable Test Case
 */
class JediFightsPlayersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\JediFightsPlayersTable
     */
    protected $JediFightsPlayers;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.JediFightsPlayers',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('JediFightsPlayers') ? [] : ['className' => JediFightsPlayersTable::class];
        $this->JediFightsPlayers = TableRegistry::getTableLocator()->get('JediFightsPlayers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->JediFightsPlayers);

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

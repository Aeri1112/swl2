<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JediTreasuresTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JediTreasuresTable Test Case
 */
class JediTreasuresTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\JediTreasuresTable
     */
    protected $JediTreasures;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.JediTreasures',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('JediTreasures') ? [] : ['className' => JediTreasuresTable::class];
        $this->JediTreasures = TableRegistry::getTableLocator()->get('JediTreasures', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->JediTreasures);

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

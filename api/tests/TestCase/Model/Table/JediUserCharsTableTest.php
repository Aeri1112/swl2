<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JediUserCharsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JediUserCharsTable Test Case
 */
class JediUserCharsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\JediUserCharsTable
     */
    protected $JediUserChars;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.JediUserChars',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('JediUserChars') ? [] : ['className' => JediUserCharsTable::class];
        $this->JediUserChars = TableRegistry::getTableLocator()->get('JediUserChars', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->JediUserChars);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}

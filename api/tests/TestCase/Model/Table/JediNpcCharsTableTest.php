<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JediNpcCharsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JediNpcCharsTable Test Case
 */
class JediNpcCharsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\JediNpcCharsTable
     */
    protected $JediNpcChars;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.JediNpcChars',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('JediNpcChars') ? [] : ['className' => JediNpcCharsTable::class];
        $this->JediNpcChars = TableRegistry::getTableLocator()->get('JediNpcChars', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->JediNpcChars);

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

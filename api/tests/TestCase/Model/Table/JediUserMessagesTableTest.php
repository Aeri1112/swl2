<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JediUserMessagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JediUserMessagesTable Test Case
 */
class JediUserMessagesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\JediUserMessagesTable
     */
    protected $JediUserMessages;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.JediUserMessages',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('JediUserMessages') ? [] : ['className' => JediUserMessagesTable::class];
        $this->JediUserMessages = TableRegistry::getTableLocator()->get('JediUserMessages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->JediUserMessages);

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

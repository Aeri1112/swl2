<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JediUserSkillsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JediUserSkillsTable Test Case
 */
class JediUserSkillsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\JediUserSkillsTable
     */
    protected $JediUserSkills;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.JediUserSkills',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('JediUserSkills') ? [] : ['className' => JediUserSkillsTable::class];
        $this->JediUserSkills = TableRegistry::getTableLocator()->get('JediUserSkills', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->JediUserSkills);

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

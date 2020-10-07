<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\JediItemsMiscTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\JediItemsMiscTable Test Case
 */
class JediItemsMiscTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\JediItemsMiscTable
     */
    protected $JediItemsMisc;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.JediItemsMisc',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('JediItemsMisc') ? [] : ['className' => JediItemsMiscTable::class];
        $this->JediItemsMisc = TableRegistry::getTableLocator()->get('JediItemsMisc', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->JediItemsMisc);

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

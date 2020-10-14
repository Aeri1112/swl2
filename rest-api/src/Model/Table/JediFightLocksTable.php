<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JediFightLocks Model
 *
 * @method \App\Model\Entity\JediFightLock newEmptyEntity()
 * @method \App\Model\Entity\JediFightLock newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\JediFightLock[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\JediFightLock get($primaryKey, $options = [])
 * @method \App\Model\Entity\JediFightLock findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\JediFightLock patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\JediFightLock[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\JediFightLock|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediFightLock saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediFightLock[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediFightLock[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediFightLock[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediFightLock[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class JediFightLocksTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('jedi_fight_locks');
        $this->setDisplayField('fightid');
        $this->setPrimaryKey('fightid');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('fightid')
            ->allowEmptyString('fightid', null, 'create');

        $validator
            ->integer('since')
            ->requirePresence('since', 'create')
            ->notEmptyString('since');

        return $validator;
    }
}

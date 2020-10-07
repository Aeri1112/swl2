<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JediEventsSingleRanking Model
 *
 * @method \App\Model\Entity\JediEventsSingleRanking newEmptyEntity()
 * @method \App\Model\Entity\JediEventsSingleRanking newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\JediEventsSingleRanking[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\JediEventsSingleRanking get($primaryKey, $options = [])
 * @method \App\Model\Entity\JediEventsSingleRanking findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\JediEventsSingleRanking patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\JediEventsSingleRanking[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\JediEventsSingleRanking|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediEventsSingleRanking saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediEventsSingleRanking[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediEventsSingleRanking[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediEventsSingleRanking[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediEventsSingleRanking[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class JediEventsSingleRankingTable extends Table
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

        $this->setTable('jedi_events_single_ranking');
        $this->setDisplayField('userid');
        $this->setPrimaryKey('userid');
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
            ->integer('userid')
            ->allowEmptyString('userid', null, 'create');

        $validator
            ->integer('points')
            ->requirePresence('points', 'create')
            ->notEmptyString('points');

        $validator
            ->integer('fights')
            ->requirePresence('fights', 'create')
            ->notEmptyString('fights');

        return $validator;
    }
}

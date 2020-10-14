<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JediAlliances Model
 *
 * @method \App\Model\Entity\JediAlliance newEmptyEntity()
 * @method \App\Model\Entity\JediAlliance newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\JediAlliance[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\JediAlliance get($primaryKey, $options = [])
 * @method \App\Model\Entity\JediAlliance findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\JediAlliance patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\JediAlliance[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\JediAlliance|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediAlliance saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediAlliance[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediAlliance[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediAlliance[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediAlliance[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class JediAlliancesTable extends Table
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

        $this->setTable('jedi_alliances');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('short')
            ->maxLength('short', 20)
            ->requirePresence('short', 'create')
            ->notEmptyString('short');

        $validator
            ->integer('pic')
            ->requirePresence('pic', 'create')
            ->notEmptyString('pic');

        $validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->integer('leader')
            ->requirePresence('leader', 'create')
            ->notEmptyString('leader');

        $validator
            ->integer('coleader')
            ->requirePresence('coleader', 'create')
            ->notEmptyString('coleader');

        $validator
            ->integer('alignment')
            ->requirePresence('alignment', 'create')
            ->notEmptyString('alignment');

        $validator
            ->integer('cash')
            ->requirePresence('cash', 'create')
            ->notEmptyString('cash');

        return $validator;
    }
}

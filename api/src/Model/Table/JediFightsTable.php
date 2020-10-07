<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JediFights Model
 *
 * @method \App\Model\Entity\JediFight newEmptyEntity()
 * @method \App\Model\Entity\JediFight newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\JediFight[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\JediFight get($primaryKey, $options = [])
 * @method \App\Model\Entity\JediFight findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\JediFight patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\JediFight[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\JediFight|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediFight saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediFight[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediFight[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediFight[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediFight[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class JediFightsTable extends Table
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

        $this->setTable('jedi_fights');
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
            ->allowEmptyString('fightid', null, 'create')
            ->add('fightid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('type')
            ->maxLength('type', 20)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->integer('opentime')
            ->requirePresence('opentime', 'create')
            ->notEmptyString('opentime');

        $validator
            ->scalar('startin')
            ->requirePresence('startin', 'create')
            ->notEmptyString('startin');

        $validator
            ->integer('bet')
            ->requirePresence('bet', 'create')
            ->notEmptyString('bet');

        $validator
            ->integer('minstr')
            ->requirePresence('minstr', 'create')
            ->notEmptyString('minstr');

        $validator
            ->integer('maxstr')
            ->requirePresence('maxstr', 'create')
            ->notEmptyString('maxstr');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['fightid']));

        return $rules;
    }
}

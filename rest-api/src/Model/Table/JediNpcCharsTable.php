<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JediNpcChars Model
 *
 * @method \App\Model\Entity\JediNpcChar newEmptyEntity()
 * @method \App\Model\Entity\JediNpcChar newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\JediNpcChar[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\JediNpcChar get($primaryKey, $options = [])
 * @method \App\Model\Entity\JediNpcChar findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\JediNpcChar patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\JediNpcChar[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\JediNpcChar|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediNpcChar saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediNpcChar[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediNpcChar[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediNpcChar[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediNpcChar[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class JediNpcCharsTable extends Table
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

        $this->setTable('jedi_npc_chars');
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
            ->requirePresence('userid', 'create')
            ->notEmptyString('userid')
            ->add('userid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('username')
            ->maxLength('username', 20)
            ->requirePresence('username', 'create')
            ->notEmptyString('username');

        $validator
            ->scalar('sex')
            ->maxLength('sex', 2)
            ->requirePresence('sex', 'create')
            ->notEmptyString('sex');

        $validator
            ->integer('health')
            ->requirePresence('health', 'create')
            ->notEmptyString('health');

        $validator
            ->integer('mana')
            ->requirePresence('mana', 'create')
            ->notEmptyString('mana');

        $validator
            ->integer('base')
            ->requirePresence('base', 'create')
            ->notEmptyString('base');

        $validator
            ->integer('item_hand')
            ->requirePresence('item_hand', 'create')
            ->notEmptyString('item_hand');

        $validator
            ->integer('fpreferences')
            ->requirePresence('fpreferences', 'create')
            ->notEmptyString('fpreferences');

        $validator
            ->integer('masterid')
            ->requirePresence('masterid', 'create')
            ->notEmptyString('masterid');

        $validator
            ->scalar('alliance')
            ->maxLength('alliance', 20)
            ->requirePresence('alliance', 'create')
            ->notEmptyString('alliance');

        $validator
            ->integer('fpreferences2')
            ->requirePresence('fpreferences2', 'create')
            ->notEmptyString('fpreferences2');

        $validator
            ->scalar('status')
            ->maxLength('status', 30)
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->integer('lastaccess')
            ->requirePresence('lastaccess', 'create')
            ->notEmptyString('lastaccess');

        $validator
            ->scalar('species')
            ->requirePresence('species', 'create')
            ->notEmptyString('species');

        $validator
            ->integer('age')
            ->requirePresence('age', 'create')
            ->notEmptyString('age');

        $validator
            ->scalar('size')
            ->maxLength('size', 5)
            ->requirePresence('size', 'create')
            ->notEmptyString('size');

        $validator
            ->scalar('homeworld')
            ->requirePresence('homeworld', 'create')
            ->notEmptyString('homeworld');

        $validator
            ->integer('energy')
            ->requirePresence('energy', 'create')
            ->notEmptyString('energy');

        $validator
            ->integer('rank')
            ->requirePresence('rank', 'create')
            ->notEmptyString('rank');

        $validator
            ->integer('actionid')
            ->requirePresence('actionid', 'create')
            ->notEmptyString('actionid');

        $validator
            ->integer('targetid')
            ->requirePresence('targetid', 'create')
            ->notEmptyString('targetid');

        $validator
            ->integer('targettime')
            ->requirePresence('targettime', 'create')
            ->notEmptyString('targettime');

        $validator
            ->integer('lastfightid')
            ->requirePresence('lastfightid', 'create')
            ->notEmptyString('lastfightid');

        $validator
            ->scalar('location')
            ->maxLength('location', 30)
            ->requirePresence('location', 'create')
            ->notEmptyString('location');

        $validator
            ->integer('pic')
            ->requirePresence('pic', 'create')
            ->notEmptyString('pic');

        $validator
            ->scalar('location2')
            ->maxLength('location2', 30)
            ->requirePresence('location2', 'create')
            ->notEmptyString('location2');

        $validator
            ->integer('tmpcast')
            ->requirePresence('tmpcast', 'create')
            ->notEmptyString('tmpcast');

        $validator
            ->integer('cash')
            ->requirePresence('cash', 'create')
            ->notEmptyString('cash');

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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->isUnique(['userid']));

        return $rules;
    }
}

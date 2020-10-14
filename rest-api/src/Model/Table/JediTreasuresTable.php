<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JediTreasures Model
 *
 * @method \App\Model\Entity\JediTreasure newEmptyEntity()
 * @method \App\Model\Entity\JediTreasure newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\JediTreasure[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\JediTreasure get($primaryKey, $options = [])
 * @method \App\Model\Entity\JediTreasure findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\JediTreasure patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\JediTreasure[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\JediTreasure|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediTreasure saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediTreasure[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediTreasure[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediTreasure[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediTreasure[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class JediTreasuresTable extends Table
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

        $this->setTable('jedi_treasures');
        $this->setDisplayField('name');
        $this->setPrimaryKey('itemid');
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
            ->integer('itemid')
            ->allowEmptyString('itemid', null, 'create');

        $validator
            ->integer('ownerid')
            ->requirePresence('ownerid', 'create')
            ->notEmptyString('ownerid');

        $validator
            ->scalar('type')
            ->maxLength('type', 20)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('img')
            ->maxLength('img', 30)
            ->requirePresence('img', 'create')
            ->notEmptyString('img');

        $validator
            ->integer('sizex')
            ->requirePresence('sizex', 'create')
            ->notEmptyString('sizex');

        $validator
            ->integer('sizey')
            ->requirePresence('sizey', 'create')
            ->notEmptyString('sizey');

        $validator
            ->integer('price')
            ->requirePresence('price', 'create')
            ->notEmptyString('price');

        $validator
            ->integer('qlvl')
            ->requirePresence('qlvl', 'create')
            ->notEmptyString('qlvl');

        $validator
            ->scalar('uni')
            ->maxLength('uni', 11)
            ->requirePresence('uni', 'create')
            ->notEmptyString('uni');

        $validator
            ->integer('crafted')
            ->requirePresence('crafted', 'create')
            ->notEmptyString('crafted');

        $validator
            ->scalar('nodrop')
            ->maxLength('nodrop', 11)
            ->requirePresence('nodrop', 'create')
            ->notEmptyString('nodrop');

        $validator
            ->integer('weight')
            ->requirePresence('weight', 'create')
            ->notEmptyString('weight');

        $validator
            ->integer('reql')
            ->requirePresence('reql', 'create')
            ->notEmptyString('reql');

        $validator
            ->integer('reqs')
            ->requirePresence('reqs', 'create')
            ->notEmptyString('reqs');

        $validator
            ->scalar('consumable')
            ->maxLength('consumable', 11)
            ->requirePresence('consumable', 'create')
            ->notEmptyString('consumable');

        $validator
            ->integer('mindmg')
            ->requirePresence('mindmg', 'create')
            ->notEmptyString('mindmg');

        $validator
            ->integer('maxdmg')
            ->requirePresence('maxdmg', 'create')
            ->notEmptyString('maxdmg');

        $validator
            ->scalar('stat1')
            ->maxLength('stat1', 30)
            ->requirePresence('stat1', 'create')
            ->notEmptyString('stat1');

        $validator
            ->scalar('stat2')
            ->maxLength('stat2', 30)
            ->requirePresence('stat2', 'create')
            ->notEmptyString('stat2');

        $validator
            ->scalar('stat3')
            ->maxLength('stat3', 30)
            ->requirePresence('stat3', 'create')
            ->notEmptyString('stat3');

        $validator
            ->scalar('stat4')
            ->maxLength('stat4', 20)
            ->requirePresence('stat4', 'create')
            ->notEmptyString('stat4');

        $validator
            ->scalar('stat5')
            ->maxLength('stat5', 20)
            ->requirePresence('stat5', 'create')
            ->notEmptyString('stat5');

        return $validator;
    }
}

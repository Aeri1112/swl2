<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JediItemsWeaponsNpc Model
 *
 * @method \App\Model\Entity\JediItemsWeaponsNpc newEmptyEntity()
 * @method \App\Model\Entity\JediItemsWeaponsNpc newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\JediItemsWeaponsNpc[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\JediItemsWeaponsNpc get($primaryKey, $options = [])
 * @method \App\Model\Entity\JediItemsWeaponsNpc findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\JediItemsWeaponsNpc patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\JediItemsWeaponsNpc[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\JediItemsWeaponsNpc|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediItemsWeaponsNpc saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediItemsWeaponsNpc[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediItemsWeaponsNpc[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediItemsWeaponsNpc[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediItemsWeaponsNpc[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class JediItemsWeaponsNpcTable extends Table
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

        $this->setTable('jedi_items_weapons_npc');
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
            ->scalar('position')
            ->maxLength('position', 11)
            ->requirePresence('position', 'create')
            ->notEmptyString('position');

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
            ->scalar('crafted')
            ->maxLength('crafted', 11)
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
            ->maxLength('stat4', 30)
            ->requirePresence('stat4', 'create')
            ->notEmptyString('stat4');

        $validator
            ->scalar('stat5')
            ->maxLength('stat5', 30)
            ->requirePresence('stat5', 'create')
            ->notEmptyString('stat5');

        return $validator;
    }
}

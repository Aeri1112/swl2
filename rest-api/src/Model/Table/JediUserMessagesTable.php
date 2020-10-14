<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JediUserMessages Model
 *
 * @method \App\Model\Entity\JediUserMessage newEmptyEntity()
 * @method \App\Model\Entity\JediUserMessage newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\JediUserMessage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\JediUserMessage get($primaryKey, $options = [])
 * @method \App\Model\Entity\JediUserMessage findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\JediUserMessage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\JediUserMessage[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\JediUserMessage|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediUserMessage saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediUserMessage[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediUserMessage[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediUserMessage[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediUserMessage[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class JediUserMessagesTable extends Table
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

        $this->setTable('jedi_user_messages');
        $this->setDisplayField('id');
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
            ->integer('send_from')
            ->requirePresence('send_from', 'create')
            ->notEmptyString('send_from');

        $validator
            ->integer('send_to')
            ->requirePresence('send_to', 'create')
            ->notEmptyString('send_to');

        $validator
            ->scalar('text')
            ->requirePresence('text', 'create')
            ->notEmptyString('text');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->date('send')
            ->requirePresence('send', 'create')
            ->notEmptyDate('send');

        return $validator;
    }
}

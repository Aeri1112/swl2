<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JediFightReports Model
 *
 * @method \App\Model\Entity\JediFightReport newEmptyEntity()
 * @method \App\Model\Entity\JediFightReport newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\JediFightReport[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\JediFightReport get($primaryKey, $options = [])
 * @method \App\Model\Entity\JediFightReport findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\JediFightReport patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\JediFightReport[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\JediFightReport|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediFightReport saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediFightReport[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediFightReport[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediFightReport[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediFightReport[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class JediFightReportsTable extends Table
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

        $this->setTable('jedi_fight_reports');
        $this->setDisplayField('md5');
        $this->setPrimaryKey('md5');
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
            ->integer('zeit')
            ->requirePresence('zeit', 'create')
            ->notEmptyString('zeit');

        $validator
            ->scalar('report')
            ->maxLength('report', 4294967295)
            ->requirePresence('report', 'create')
            ->notEmptyString('report');

        $validator
            ->scalar('md5')
            ->maxLength('md5', 50)
            ->allowEmptyString('md5', null, 'create');

        return $validator;
    }
}

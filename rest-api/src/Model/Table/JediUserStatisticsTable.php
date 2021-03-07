<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JediUserStatistics Model
 *
 * @method \App\Model\Entity\JediUserStatistic newEmptyEntity()
 * @method \App\Model\Entity\JediUserStatistic newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\JediUserStatistic[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\JediUserStatistic get($primaryKey, $options = [])
 * @method \App\Model\Entity\JediUserStatistic findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\JediUserStatistic patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\JediUserStatistic[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\JediUserStatistic|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediUserStatistic saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediUserStatistic[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediUserStatistic[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediUserStatistic[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediUserStatistic[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class JediUserStatisticsTable extends Table
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

        $this->setTable('jedi_user_statistics');
        $this->setDisplayField('userid');
        $this->setPrimaryKey('userid');
        $this->belongsTo('JediUserChars')
            ->setForeignKey('userid')
            ->setJoinType('INNER');
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
            ->integer('totalwins')
            ->requirePresence('totalwins', 'create')
            ->notEmptyString('totalwins');

        $validator
            ->integer('arenawins')
            ->requirePresence('arenawins', 'create')
            ->notEmptyString('arenawins');

        $validator
            ->integer('npcwins')
            ->requirePresence('npcwins', 'create')
            ->notEmptyString('npcwins');

        return $validator;
    }
}

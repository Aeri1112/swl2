<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * JediUserSkills Model
 *
 * @method \App\Model\Entity\JediUserSkill newEmptyEntity()
 * @method \App\Model\Entity\JediUserSkill newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\JediUserSkill[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\JediUserSkill get($primaryKey, $options = [])
 * @method \App\Model\Entity\JediUserSkill findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\JediUserSkill patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\JediUserSkill[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\JediUserSkill|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediUserSkill saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\JediUserSkill[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediUserSkill[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediUserSkill[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\JediUserSkill[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class JediUserSkillsTable extends Table
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

        $this->setTable('jedi_user_skills');
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
            ->allowEmptyString('userid', null, 'create')
            ->add('userid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->integer('xp')
            ->requirePresence('xp', 'create')
            ->notEmptyString('xp');

        $validator
            ->integer('level')
            ->requirePresence('level', 'create')
            ->notEmptyString('level');

        $validator
            ->integer('rsp')
            ->requirePresence('rsp', 'create')
            ->notEmptyString('rsp');

        $validator
            ->integer('rfp')
            ->requirePresence('rfp', 'create')
            ->notEmptyString('rfp');

        $validator
            ->integer('side')
            ->requirePresence('side', 'create')
            ->notEmptyString('side');

        $validator
            ->integer('cns')
            ->requirePresence('cns', 'create')
            ->notEmptyString('cns');

        $validator
            ->integer('agi')
            ->requirePresence('agi', 'create')
            ->notEmptyString('agi');

        $validator
            ->integer('spi')
            ->requirePresence('spi', 'create')
            ->notEmptyString('spi');

        $validator
            ->integer('itl')
            ->requirePresence('itl', 'create')
            ->notEmptyString('itl');

        $validator
            ->integer('tac')
            ->requirePresence('tac', 'create')
            ->notEmptyString('tac');

        $validator
            ->integer('dex')
            ->requirePresence('dex', 'create')
            ->notEmptyString('dex');

        $validator
            ->integer('lsa')
            ->requirePresence('lsa', 'create')
            ->notEmptyString('lsa');

        $validator
            ->integer('lsd')
            ->requirePresence('lsd', 'create')
            ->notEmptyString('lsd');

        $validator
            ->integer('fspee')
            ->requirePresence('fspee', 'create')
            ->notEmptyString('fspee');

        $validator
            ->integer('fjump')
            ->requirePresence('fjump', 'create')
            ->notEmptyString('fjump');

        $validator
            ->integer('fpull')
            ->requirePresence('fpull', 'create')
            ->notEmptyString('fpull');

        $validator
            ->integer('fpush')
            ->requirePresence('fpush', 'create')
            ->notEmptyString('fpush');

        $validator
            ->integer('fseei')
            ->requirePresence('fseei', 'create')
            ->notEmptyString('fseei');

        $validator
            ->integer('fsabe')
            ->requirePresence('fsabe', 'create')
            ->notEmptyString('fsabe');

        $validator
            ->integer('fpers')
            ->requirePresence('fpers', 'create')
            ->notEmptyString('fpers');

        $validator
            ->integer('fproj')
            ->requirePresence('fproj', 'create')
            ->notEmptyString('fproj');

        $validator
            ->integer('fblin')
            ->requirePresence('fblin', 'create')
            ->notEmptyString('fblin');

        $validator
            ->integer('fconf')
            ->requirePresence('fconf', 'create')
            ->notEmptyString('fconf');

        $validator
            ->integer('fheal')
            ->requirePresence('fheal', 'create')
            ->notEmptyString('fheal');

        $validator
            ->integer('ftheal')
            ->requirePresence('ftheal', 'create')
            ->notEmptyString('ftheal');

        $validator
            ->integer('fprot')
            ->requirePresence('fprot', 'create')
            ->notEmptyString('fprot');

        $validator
            ->integer('fabso')
            ->requirePresence('fabso', 'create')
            ->notEmptyString('fabso');

        $validator
            ->integer('frvtl')
            ->requirePresence('frvtl', 'create')
            ->notEmptyString('frvtl');

        $validator
            ->integer('fthrow')
            ->requirePresence('fthrow', 'create')
            ->notEmptyString('fthrow');

        $validator
            ->integer('frage')
            ->requirePresence('frage', 'create')
            ->notEmptyString('frage');

        $validator
            ->integer('fgrip')
            ->requirePresence('fgrip', 'create')
            ->notEmptyString('fgrip');

        $validator
            ->integer('fdrain')
            ->requirePresence('fdrain', 'create')
            ->notEmptyString('fdrain');

        $validator
            ->integer('fthun')
            ->requirePresence('fthun', 'create')
            ->notEmptyString('fthun');

        $validator
            ->integer('fchai')
            ->requirePresence('fchai', 'create')
            ->notEmptyString('fchai');

        $validator
            ->integer('fdest')
            ->requirePresence('fdest', 'create')
            ->notEmptyString('fdest');

        $validator
            ->integer('fdead')
            ->requirePresence('fdead', 'create')
            ->notEmptyString('fdead');

        $validator
            ->integer('ftnrg')
            ->requirePresence('ftnrg', 'create')
            ->notEmptyString('ftnrg');

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
        $rules->add($rules->isUnique(['userid']));

        return $rules;
    }
}

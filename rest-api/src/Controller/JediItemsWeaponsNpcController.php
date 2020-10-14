<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * JediItemsWeaponsNpc Controller
 *
 * @property \App\Model\Table\JediItemsWeaponsNpcTable $JediItemsWeaponsNpc
 *
 * @method \App\Model\Entity\JediItemsWeaponsNpc[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class JediItemsWeaponsNpcController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $jediItemsWeaponsNpc = $this->paginate($this->JediItemsWeaponsNpc);

        $this->set(compact('jediItemsWeaponsNpc'));
    }

    /**
     * View method
     *
     * @param string|null $id Jedi Items Weapons Npc id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $jediItemsWeaponsNpc = $this->JediItemsWeaponsNpc->get($id, [
            'contain' => [],
        ]);

        $this->set('jediItemsWeaponsNpc', $jediItemsWeaponsNpc);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $jediItemsWeaponsNpc = $this->JediItemsWeaponsNpc->newEmptyEntity();
        if ($this->request->is('post')) {
            $jediItemsWeaponsNpc = $this->JediItemsWeaponsNpc->patchEntity($jediItemsWeaponsNpc, $this->request->getData());
            if ($this->JediItemsWeaponsNpc->save($jediItemsWeaponsNpc)) {
                $this->Flash->success(__('The jedi items weapons npc has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The jedi items weapons npc could not be saved. Please, try again.'));
        }
        $this->set(compact('jediItemsWeaponsNpc'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Jedi Items Weapons Npc id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $jediItemsWeaponsNpc = $this->JediItemsWeaponsNpc->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $jediItemsWeaponsNpc = $this->JediItemsWeaponsNpc->patchEntity($jediItemsWeaponsNpc, $this->request->getData());
            if ($this->JediItemsWeaponsNpc->save($jediItemsWeaponsNpc)) {
                $this->Flash->success(__('The jedi items weapons npc has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The jedi items weapons npc could not be saved. Please, try again.'));
        }
        $this->set(compact('jediItemsWeaponsNpc'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Jedi Items Weapons Npc id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $jediItemsWeaponsNpc = $this->JediItemsWeaponsNpc->get($id);
        if ($this->JediItemsWeaponsNpc->delete($jediItemsWeaponsNpc)) {
            $this->Flash->success(__('The jedi items weapons npc has been deleted.'));
        } else {
            $this->Flash->error(__('The jedi items weapons npc could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

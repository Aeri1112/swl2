<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * JediItemsJewelryNpc Controller
 *
 * @property \App\Model\Table\JediItemsJewelryNpcTable $JediItemsJewelryNpc
 *
 * @method \App\Model\Entity\JediItemsJewelryNpc[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class JediItemsJewelryNpcController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $jediItemsJewelryNpc = $this->paginate($this->JediItemsJewelryNpc);

        $this->set(compact('jediItemsJewelryNpc'));
    }

    /**
     * View method
     *
     * @param string|null $id Jedi Items Jewelry Npc id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $jediItemsJewelryNpc = $this->JediItemsJewelryNpc->get($id, [
            'contain' => [],
        ]);

        $this->set('jediItemsJewelryNpc', $jediItemsJewelryNpc);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $jediItemsJewelryNpc = $this->JediItemsJewelryNpc->newEmptyEntity();
        if ($this->request->is('post')) {
            $jediItemsJewelryNpc = $this->JediItemsJewelryNpc->patchEntity($jediItemsJewelryNpc, $this->request->getData());
            if ($this->JediItemsJewelryNpc->save($jediItemsJewelryNpc)) {
                $this->Flash->success(__('The jedi items jewelry npc has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The jedi items jewelry npc could not be saved. Please, try again.'));
        }
        $this->set(compact('jediItemsJewelryNpc'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Jedi Items Jewelry Npc id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $jediItemsJewelryNpc = $this->JediItemsJewelryNpc->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $jediItemsJewelryNpc = $this->JediItemsJewelryNpc->patchEntity($jediItemsJewelryNpc, $this->request->getData());
            if ($this->JediItemsJewelryNpc->save($jediItemsJewelryNpc)) {
                $this->Flash->success(__('The jedi items jewelry npc has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The jedi items jewelry npc could not be saved. Please, try again.'));
        }
        $this->set(compact('jediItemsJewelryNpc'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Jedi Items Jewelry Npc id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $jediItemsJewelryNpc = $this->JediItemsJewelryNpc->get($id);
        if ($this->JediItemsJewelryNpc->delete($jediItemsJewelryNpc)) {
            $this->Flash->success(__('The jedi items jewelry npc has been deleted.'));
        } else {
            $this->Flash->error(__('The jedi items jewelry npc could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

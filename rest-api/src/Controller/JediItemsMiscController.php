<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * JediItemsMisc Controller
 *
 * @property \App\Model\Table\JediItemsMiscTable $JediItemsMisc
 *
 * @method \App\Model\Entity\JediItemsMisc[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class JediItemsMiscController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $jediItemsMisc = $this->paginate($this->JediItemsMisc);

        $this->set(compact('jediItemsMisc'));
    }

    /**
     * View method
     *
     * @param string|null $id Jedi Items Misc id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $jediItemsMisc = $this->JediItemsMisc->get($id, [
            'contain' => [],
        ]);

        $this->set('jediItemsMisc', $jediItemsMisc);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $jediItemsMisc = $this->JediItemsMisc->newEmptyEntity();
        if ($this->request->is('post')) {
            $jediItemsMisc = $this->JediItemsMisc->patchEntity($jediItemsMisc, $this->request->getData());
            if ($this->JediItemsMisc->save($jediItemsMisc)) {
                $this->Flash->success(__('The jedi items misc has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The jedi items misc could not be saved. Please, try again.'));
        }
        $this->set(compact('jediItemsMisc'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Jedi Items Misc id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $jediItemsMisc = $this->JediItemsMisc->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $jediItemsMisc = $this->JediItemsMisc->patchEntity($jediItemsMisc, $this->request->getData());
            if ($this->JediItemsMisc->save($jediItemsMisc)) {
                $this->Flash->success(__('The jedi items misc has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The jedi items misc could not be saved. Please, try again.'));
        }
        $this->set(compact('jediItemsMisc'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Jedi Items Misc id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $jediItemsMisc = $this->JediItemsMisc->get($id);
        if ($this->JediItemsMisc->delete($jediItemsMisc)) {
            $this->Flash->success(__('The jedi items misc has been deleted.'));
        } else {
            $this->Flash->error(__('The jedi items misc could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

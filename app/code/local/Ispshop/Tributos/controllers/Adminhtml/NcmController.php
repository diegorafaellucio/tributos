<?php

class Ispshop_Tributos_Adminhtml_NcmController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        // Let's call our initAction method which will set some basic params for each action
        $this->_initAction()
                ->renderLayout();
    }

    public function newAction() {
        // We just forward the new action to a blank edit form
        $this->_forward('edit');
    }

    public function deleteAction() {
        $this->_initAction();

        // Get id if available

        $product_id = $this->getRequest()->getParam('product_id');
        $model = Mage::getModel('ispshop_tributos/ncm');
        $collection = $model->getCollection()->addFieldToFilter('product_id', $product_id);



        if ($product_id) {

            foreach ($collection as $mac) {
                $entity_id = $mac->getId();
                $model->load($entity_id);
                $model->delete();
            }
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('O Cadastro e Substituiçao Tributaria foi removido com sucesso.'));
            $this->_redirect('*/catalog_product/edit/id/' . $product_id);

            return;
        }
    }

    public function updateAction() {
        $this->_initAction();

        // Get id if available

        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('ispshop_tributos/ncm');

        if ($id) {
            // Load record
            $model->load($id);

            // Check if record is loaded
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Este Cadastro de Substituição Tributária não existe.'));
                $this->_redirect('*/*/');

                return;
            }
        }

        $this->_title($model->getId() ? $model->getName() : $this->__('Atualizar Cadastro de Substituição Tributária'));

        $data = Mage::getSingleton('adminhtml/session')->getNcmData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('ispshop_tributos', $model);

        $this->_initAction()
                ->_addBreadcrumb($id ? $this->__('Editar Substituição Tributária') : $this->__('Nova Substituição Tributária'), $id ? $this->__('Editar Substituição Tributária') : $this->__('Nova Substituição Tributária'))
                ->_addContent($this->getLayout()->createBlock('ispshop_tributos/adminhtml_ncm_edit')->setData('action', $this->getUrl('*/*/save')))
                ->renderLayout();
    }

    public function editAction() {
        $this->_initAction();

        // Get id if available

        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('ispshop_tributos/ncm');

        if ($id) {
            // Load record
            $model->load($id);

            // Check if record is loaded
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Este Cadastro de Substituição Tributária não existe.'));
                $this->_redirect('*/*/');

                return;
            }
        }

        $this->_title($model->getId() ? $model->getName() : $this->__('Editar Cadastro de Substituição Tributária'));

        $data = Mage::getSingleton('adminhtml/session')->getNcmData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('ispshop_tributos', $model);

        $this->_initAction()
                ->_addBreadcrumb($id ? $this->__('Editar Substituição Tributária') : $this->__('Nova Substituição Tributária'), $id ? $this->__('Editar Substituição Tributária') : $this->__('Nova Substituição Tributária'))
                ->_addContent($this->getLayout()->createBlock('ispshop_tributos/adminhtml_ncm_edit')->setData('action', $this->getUrl('*/*/save')))
                ->renderLayout();
    }

    public function saveAction() {
        if ($postData = $this->getRequest()->getPost()) {



            $redirect_link = '';
            $ncm = $postData;

            try {
                if (isset($postData['id'])) {
                    $redirect_link = '*/catalog_product/edit/id/' . $postData['product_id'];
                    $model = Mage::getSingleton('ispshop_tributos/ncm');
                    $model->setData($postData);

                    $model->save();
                } else {

                    for ($index = 0; $index < count($ncm) - 1; $index++) {




                        $tempNCM = $ncm['ncm' . $index];
                        $tempNcmArray = explode(":", $tempNCM);


                        if ($index == 0) {
                            if (count($tempNcmArray) == 4) {
                                $redirect_link = '*/catalog_product/edit/id/' . $tempNcmArray[2];
                            } else {
                                $redirect_link = '*/catalog_product/edit/id/' . $tempNcmArray[1];
                            }
                        }

                        if (count($tempNcmArray) == 4) {
                            $data = array('id' => $tempNcmArray[0], 'id_monitoramento_ncm' => $tempNcmArray[1], 'product_id' => $tempNcmArray[2], 'region_id' => $tempNcmArray[3]);
                        } else {
                            $data = array('id_monitoramento_ncm' => $tempNcmArray[0], 'product_id' => $tempNcmArray[1], 'region_id' => $tempNcmArray[2]);
                        }


                        $model = Mage::getSingleton('ispshop_tributos/ncm');
                        $model->setData($data);



                        $model->save();
                    }
                }

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Os dados de Substituição Tributária foram cadastrados com sucesso.'));
                $this->_redirect($redirect_link);
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('Um erro ocorreu enquanto os dados de Substituição Tributária estavam sendo salvos.'));
            }
        }

        Mage::getSingleton('adminhtml/session')->setNcmData($postData);
        $this->_redirectReferer();
    }

    public function messageAction() {
        $data = Mage::getModel('ispshop_tributos/ncm')->load($this->getRequest()->getParam('id'));
        echo $data->getContent();
    }

    /**
     * Initialize action
     *
     * Here, we set the breadcrumbs and the active menu
     *
     * @return Mage_Adminhtml_Controller_Action
     */
    protected function _initAction() {

        $this->loadLayout()
                // Make the active menu match the menu config nodes (without 'children' inbetween)
                ->_setActiveMenu('ispshop_tributos/ispshop_triobutos_log')
                ->_title($this->__('Substituição Tributária'))->_title($this->__('Controle'))
                ->_addBreadcrumb($this->__('Substituição Tributária'), $this->__('Substituição Tributária'))
                ->_addBreadcrumb($this->__('Controle'), $this->__('Controle'));


        return $this;
    }

    /**
     * Check currently called action by permissions for current user
     *
     * @return bool
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('sales/ispshop_tributos_ncm');
    }

}

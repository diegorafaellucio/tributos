<?php

class Ispshop_Tributos_Adminhtml_UpdateController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        // Let's call our initAction method which will set some basic params for each action
        $this->_initAction()
                ->renderLayout();
    }

    public function saveAction() {

        $helper = Mage::helper('ispshop_tributos/ncm_update');
        $model = Mage::getModel('ispshop_tributos/monitoramento');
        $modelNcm = Mage::getModel('ispshop_tributos/ncm');
        $modelLog = Mage::getModel('ispshop_tributos/log');
        $hasChange = false;

        try {
            $uploader = new Varien_File_Uploader('update_file');
            $uploader->setAllowedExtensions(array('csv'));
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);
            $path = Mage::getBaseDir('var') . DS . 'temp' . DS;
            $csvName = $_FILES['update_file']['name'];
            $uploader->save($path, $csvName);

            $ncmData = null;
            $logData = null;
            $dados = array();
            $handle = fopen($path . $csvName, "r");

            $updates = fopen($path . 'updates.csv', 'w');


            fputcsv($updates, array('NCM', 'Campos Atualizados', 'Produto', 'Id do Produto', 'Estado', 'Tipo'));
            $row = 0;
            $updatedFields = false;

            while (($data = fgetcsv($handle, 3000, ";")) !== FALSE) {
                $num = count($data); //quantidadde de campos
                $row++; //incremento nas linhas
                for ($i = 0; $i < $num; $i++) {
                    if ($row > 1) {
                        $p = str_replace("'", "", iconv("WINDOWS-1252", "UTF-8", $data[$i])); //Retirar aspas simples do texto
                        $p2 = str_replace('"', "", $p); //retirar aspas duplas do texto
                        $dados[] .= $p2;
                    }
                }
                if (count($dados) > 1) {

                    $ncmData = null;
                    $chave = $dados[31];

                    $item = $model->load($chave, 'chave');

                    if (count($item->getData()) > 0) {

                        $updatedFields = $helper->getUpdatedFields($item, $dados);

                        if (count($updatedFields) > 0) {
                            $hasChange = true;

                            $collectionNcm = $modelNcm->getCollection()->addFieldToFilter('id_monitoramento_ncm', $item->getData('id'));
                            foreach ($collectionNcm as $ncm) {
                                fputcsv($updates, array($dados[1], $helper->getUpdatedFieldsToString($updatedFields), Mage::getSingleton('catalog/product')->getResource()->getAttributeRawValue($ncm->getData('product_id'), 'name', Mage::app()->getStore()), $ncm->getData('product_id'), $item->getEstadoEntrada(), 'Atualização'));
                                $logData = array('ncm' => $dados[1], 'updated_fields' => $helper->getUpdatedFieldsToString($updatedFields), 'country_region' => $item->getEstadoEntrada(), 'type' => 'Atualização', 'responsable' => Mage::getSingleton('admin/session')->getUser()->getUsername(), 'created_at' => Mage::getSingleton('core/date')->gmtDate(), 'product_id' => $ncm->getData('product_id'));
                                $modelLog->setData($logData);
                                $modelLog->save();
                                $modelLog->unsetData();
                            }
                            $ncmData = array('id' => $item->getData('id'), 'operacao' => $dados[0], 'ncm' => $dados[1], 'minha_descricao' => $dados[2], 'meu_codigo' => $dados[3], 'descricao_tipi' => $dados[4], 'ipi' => $dados[5], 'obs_ipi' => $dados[6], 'base_legal_ipi' => $dados[7], 'carga_trib_media_nacional' => $dados[8], 'carga_trib_media_importada' => $dados[9], 'desoneracao_folha_pagamento' => $dados[10], 'possui_similar_nacional' => $dados[11], 'obs_similar_nacional' => $dados[12], 'estado_saida' => $dados[13], 'estado_entrada' => $dados[14], 'aliquota_interestadual' => $dados[15], 'aliquota_interna' => $dados[16], 'fundo_pobreza' => $dados[17], 'norma_st' => $dados[18], 'ncm_norma' => $dados[19], 'descricao_norma' => $dados[20], 'segmento' => $dados[21], 'mva' => $dados[22], 'mva_ajustada' => $dados[23], 'mva_ajustada_4' => $dados[24], 'obs_st' => $dados[25], 'beneficios_st' => $dados[26], 'obs_variacao_st' => $dados[27], 'mva_positiva' => $dados[28], 'mva_negativa' => $dados[29], 'mva_neutra' => $dados[30]);
                            $model->setData($ncmData);
                            $model->save();
                            $model->unsetData();
                        }
                    } else {

                        $hasChange = true;

                        fputcsv($updates, array($dados[1], 'Todos os campos foram cadastrados', '', '', $dados[14], 'Novo'));
                        $logData = array('ncm' => $dados[1], 'updated_fields' => 'Todos os campos foram cadastrados', 'country_region' => $dados[14], 'type' => 'Novo', 'responsable' => Mage::getSingleton('admin/session')->getUser()->getUsername(), 'created_at' => Mage::getSingleton('core/date')->gmtDate(), 'product_id' => '');
                        $modelLog->setData($logData);
                        $modelLog->save();
                        $modelLog->unsetData();


                        $ncmData = array('operacao' => $dados[0], 'ncm' => $dados[1], 'minha_descricao' => $dados[2], 'meu_codigo' => $dados[3], 'descricao_tipi' => $dados[4], 'ipi' => $dados[5], 'obs_ipi' => $dados[6], 'base_legal_ipi' => $dados[7], 'carga_trib_media_nacional' => $dados[8], 'carga_trib_media_importada' => $dados[9], 'desoneracao_folha_pagamento' => $dados[10], 'possui_similar_nacional' => $dados[11], 'obs_similar_nacional' => $dados[12], 'estado_saida' => $dados[13], 'estado_entrada' => $dados[14], 'aliquota_interestadual' => $dados[15], 'aliquota_interna' => $dados[16], 'fundo_pobreza' => $dados[17], 'norma_st' => $dados[18], 'ncm_norma' => $dados[19], 'descricao_norma' => $dados[20], 'segmento' => $dados[21], 'mva' => $dados[22], 'mva_ajustada' => $dados[23], 'mva_ajustada_4' => $dados[24], 'obs_st' => $dados[25], 'beneficios_st' => $dados[26], 'obs_variacao_st' => $dados[27], 'mva_positiva' => $dados[28], 'mva_negativa' => $dados[29], 'mva_neutra' => $dados[30], 'chave' => $dados[31]);
                        $model->setData($ncmData);
                        $model->save();
                        $model->unsetData();
                    }
                }
                $dados = array(); /* reiniciando o array dados */
            }

            fclose($handle);
            if ($hasChange) {
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('A tabela de monitoramento foi atualizada com sucesso. <a href="' . Mage::helper("adminhtml")->getUrl("adminhtml/update/download") . '" download>Clique aqui</a> para baixar o arquivo contento a lista de dados atualizados'));
            } else {
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Não houve alterações nos dados de substituição tributária'));
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirectReferer();
        }


        $this->_redirectReferer();
    }

    public function downloadAction() {

        $filepath = Mage::getBaseDir('var') . DS . 'temp' . DS . 'updates.csv';

        if (!is_file($filepath) || !is_readable($filepath)) {
            throw new Exception ( );
        }
        $this->getResponse()
                ->setHttpResponseCode(200)
                ->setHeader('Pragma', 'public', true)
                ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
                ->setHeader('Content-type', 'application/force-download')
                ->setHeader('Content-Length', filesize($filepath))
                ->setHeader('Content-Disposition', 'inline' . '; filename=' . basename($filepath));
        $this->getResponse()->clearBody();
        $this->getResponse()->sendHeaders();
        readfile($filepath);
        //exit(0);
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
                ->_title($this->__('Substituição Tributária'))->_title($this->__('Atualização'))
                ->_addBreadcrumb($this->__('Substituição Tributária'), $this->__('Substituição Tributária'))
                ->_addBreadcrumb($this->__('Atualização'), $this->__('Atualização'));

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

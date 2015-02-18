<?php

class Ispshop_Tributos_Block_Adminhtml_Ncm_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    /**
     * Init class
     */
    public function __construct() {
        parent::__construct();

        $this->setId('ispshop_tributos_ncm_form');
        $this->setTitle($this->__('NCM'));
    }

    /**
     * Setup form fields for inserts/updates
     *
     * return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm() {
        $model = Mage::registry('ispshop_tributos');
        $_resource = Mage::getSingleton('catalog/product')->getResource();


        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post'
        ));




        if ($model->getId()) {

            $fieldset = $form->addFieldset('base_fieldset', array(
                'legend' => Mage::helper('checkout')->__('Informações de Substituição Tributária'),
                'class' => 'fieldset-wide',
            ));


            $name = $_resource->getAttributeRawValue($model->getProductId(), 'name', Mage::app()->getStore());

            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
                'value' => $this->__($model->getId()),
            ));

            $fieldset->addField('product_id', 'hidden', array(
                'name' => 'product_id',
                'value' => $this->__($model->getProductId()),
            ));

            $fieldset->addField('id_name', 'note', array(
                'text' => $this->__($model->getId()),
                'label' => 'Id',
            ));

            $fieldset->addField('product_name', 'note', array(
                'text' => $this->__($name),
                'label' => 'Produto',
            ));

            $fieldset->addField('region_id', 'note', array(
                'text' => $this->__($model->getRegionId()),
                'label' => 'Estado',
            ));

            $fieldset->addField('aliquota_icms_interno', 'text', array(
                'name' => 'aliquota_icms_interno',
                'label' => Mage::helper('ispshop_tributos')->__('ICMS Interno'),
                'title' => Mage::helper('ispshop_tributos')->__('ICMS Interno'),
                'required' => false,
                'value' => $model->getAliquotaIcmsInterno(),
            ));

            $fieldset->addField('aliquota_icms_interestadual', 'text', array(
                'name' => 'aliquota_icms_interestadual',
                'label' => Mage::helper('ispshop_tributos')->__('ICMS Externo'),
                'title' => Mage::helper('ispshop_tributos')->__('ICMS Externo'),
                'required' => false,
                'value' => $model->getAliquotaIcmsInterestadual(),
            ));

            $fieldset->addField('mva_ajustada', 'text', array(
                'name' => 'mva_ajustada',
                'label' => Mage::helper('ispshop_tributos')->__('MVA Ajustada'),
                'title' => Mage::helper('ispshop_tributos')->__('MVA Ajustada'),
                'required' => false,
                'value' => $model->getMvaAjustada(),
            ));

            $fieldset->addField('mva_ajustada_4', 'text', array(
                'name' => 'mva_ajustada_4',
                'label' => Mage::helper('ispshop_tributos')->__('MVA Ajustada 4'),
                'title' => Mage::helper('ispshop_tributos')->__('MVA Ajustada 4'),
                'required' => false,
                'value' => $model->mva_ajustada_4,
            ));

            $fieldset->addField('personalizada', 'text', array(
                'name' => 'personalizada',
                'label' => Mage::helper('ispshop_tributos')->__('Aliquota Personalizada'),
                'title' => Mage::helper('ispshop_tributos')->__('Aliquota Personalizada'),
                'required' => false,
                'value' => $model->getPersonalizada(),
            ));

            $fieldset->addField('observacao', 'textarea', array(
                'label' => 'Observação',
                'name' => 'observacao',
                'value' => '',
            ));
        } else {
            //$request = $this->getRequest();
            $states = Mage::getModel('directory/region')->getCollection()->addFieldToFilter('country_id', 'BR');
            $ncm = $_resource->getAttributeRawValue($this->getRequest()->getParam('product_id'), 'ncm', Mage::app()->getStore());
            $totalNCM = 0;
            $data = array();
            foreach ($states as $state) {

                $fieldset = $form->addFieldset('fieldset_' . $state->default_name, array(
                    'legend' => Mage::helper('checkout')->__($state->default_name),
                    'class' => 'fieldset-wide ncm-edit',
                ));

                $estado_entrada = $state->code;
                $options = array();
                $monitoramentoItens = Mage::getModel('ispshop_tributos/monitoramento')->getCollection()->addFieldToFilter('ncm', $ncm)->addFieldToFilter('estado_entrada', $estado_entrada);

                $isUpdate = Mage::getModel('ispshop_tributos/ncm')->getCollection()->addFieldToFilter('product_id', $this->getRequest()->getParam('product_id'))->addFieldToFilter('region_id', $estado_entrada)->getFirstItem();

                foreach ($monitoramentoItens as $item) {
                    if (count($isUpdate->getData()) == 0) {
                        $options[] = array('value' => $item->id . ':' . $this->getRequest()->getParam('product_id') . ':' . $item->estado_entrada, 'label' => $item->ncm . "-" . $item->ncm_norma . "-" . $item->descricao_tipi . "-" . $item->descricao_norma . "-" . $item->aliquota_externa . "-" . $item->aliquota_interna . "-" . $item->mva . "-" . $item->mva_ajustada . "-" . $item->mva_ajustada_4);
                    } else {
                        $options[] = array('value' => $isUpdate->getId() . ':' . $item->id . ':' . $this->getRequest()->getParam('product_id') . ':' . $item->estado_entrada, 'label' => $item->ncm . "-" . $item->ncm_norma . "-" . $item->descricao_tipi . "-" . $item->descricao_norma . "-" . $item->aliquota_externa . "-" . $item->aliquota_interna . "-" . $item->mva . "-" . $item->mva_ajustada . "-" . $item->mva_ajustada_4);
                    }
                }




                if (count($options) > 0) {

                    $fieldset->addField('ncm' . $totalNCM, 'radios', array(
                        'label' => 'Substituiçao Tributaritária',
                        'name' => 'ncm' . $totalNCM,
                        'class' => 'input-radio',
                        'value' => '1',
                        'required' => true,
                        'values' => $options,
                        'disabled' => false,
                        'style' => "display:inline-block;clear:right",
                        'readonly' => false,
                        'tabindex' => 1
                    ));


                    if (count($isUpdate->getData()) != 0) {
                        $data['ncm' . $totalNCM] = $isUpdate->getId() . ':' . $isUpdate->getIdMonitoramentoNcm() . ':' . $isUpdate->getProductId() . ':' . $isUpdate->getRegionId();
                    }

                    $totalNCM++;
                }
            }
        }

        if ($model->getEntitytId()) {
            $form->setValues($model->getData());
        } else {
            $form->setValues($data);
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}

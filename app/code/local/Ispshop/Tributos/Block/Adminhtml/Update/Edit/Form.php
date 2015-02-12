<?php

class Ispshop_Tributos_Block_Adminhtml_Update_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

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

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
                )
        );

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('checkout')->__('Informações de Substituição Tributária'),
            'class' => 'fieldset-wide',
        ));

        $fieldset->addField('update_file', 'file', array(
            'label' => Mage::helper('ispshop_tributos')->__('Selecione o arquivo de atualização'),
            'name' => 'update_file',
        ));



        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}

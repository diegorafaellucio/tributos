<?php
class Ispshop_Tributos_Block_Adminhtml_Ncm_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Init class
     */
    public function __construct()
    {  
        $this->_blockGroup = 'ispshop_tributos';
        $this->_controller = 'adminhtml_ncm';
     
        parent::__construct();
     
        $this->_updateButton('save', 'label', $this->__('Salvar Informação de Substituição Tributária'));
        $this->_removeButton('delete');
    }  
     
    /**
     * Get Header text
     *
     * @return string
     */
    public function getHeaderText()
    {  
        if (Mage::registry('ispshop_tributos')->getId()) {
            return $this->__('Editar Substituição Tributária');
        }  
        else {
            return $this->__('Novo Cadastro de Substituição Tributária');
        }  
    }  
}
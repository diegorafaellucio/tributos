<?php
class Ispshop_Tributos_Block_Adminhtml_Update extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        // The blockGroup must match the first half of how we call the block, and controller matches the second half
        // ie. foo_bar/adminhtml_baz
        $this->_blockGroup = 'ispshop_tributos';
        $this->_controller = 'adminhtml_update';
        $this->_headerText = $this->__('Atualizar Controle de Substituição Tributária');
         
        parent::__construct();
        
        $this->removeButton('back');
        $this->removeButton('save');
        $this->removeButton('reset');
        
       
        $this->_addButton('process', array(
            'label'     => Mage::helper('core')->__('Atualizar'),
            'class'     => 'save',
            'onclick'   => 'editForm.submit();'
        ), 1);
    }
}
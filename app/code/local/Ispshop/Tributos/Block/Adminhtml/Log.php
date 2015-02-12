<?php
class Ispshop_Tributos_Block_Adminhtml_Log extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        // The blockGroup must match the first half of how we call the block, and controller matches the second half
        // ie. foo_bar/adminhtml_baz
        $this->_blockGroup = 'ispshop_tributos';
        $this->_controller = 'adminhtml_log';
        $this->_headerText = $this->__('Logs de Atualização');
         
        parent::__construct();
        
         $this->_removeButton('add');
    }
}
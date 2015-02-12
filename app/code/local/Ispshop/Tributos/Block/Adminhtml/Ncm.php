<?php
class Ispshop_Tributos_Block_Adminhtml_Ncm extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        // The blockGroup must match the first half of how we call the block, and controller matches the second half
        // ie. foo_bar/adminhtml_baz
        $this->_blockGroup = 'ispshop_tributos';
        $this->_controller = 'adminhtml_ncm';
        $this->_headerText = $this->__('Controle de Substituição Tributária');
         
        parent::__construct();
    }
}
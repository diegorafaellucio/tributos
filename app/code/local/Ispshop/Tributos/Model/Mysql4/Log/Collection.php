<?php
class Ispshop_Tributos_Model_Mysql4_Log_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {   
        $this->_init('ispshop_tributos/log');
    }   
}
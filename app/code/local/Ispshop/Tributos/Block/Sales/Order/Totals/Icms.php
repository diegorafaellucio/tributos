<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Ispshop_Tributos_Block_Sales_Order_Totals_Icms
    extends Mage_Core_Block_Abstract
{
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * Add this total to parent
     */
    public function initTotals()
    {
        if ((float)$this->getSource()->getAmountIcms() == 0) {
            return $this;
        }
        $Icms = new Varien_Object(array(
            'code'  => 'icms',
            'field' => 'icms_amount',
            'value' => $this->getSource()->getAmountIcms(),
            'label' => $this->__('ICMS')
        ));
        
        $this->getParentBlock()->addTotalBefore($Icms, 'shipping');
        return $this;
    }
}
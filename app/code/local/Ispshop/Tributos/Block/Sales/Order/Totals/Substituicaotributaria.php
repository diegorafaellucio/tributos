<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Ispshop_Tributos_Block_Sales_Order_Totals_Substituicaotributaria
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
        if ((float)$this->getSource()->getAmountSt() == 0) {
            return $this;
        }
        $totalST = new Varien_Object(array(
            'code'  => 'substituicao_tributaria',
            'field' => 'substituicao_tributaria_amount',
            'value' => $this->getSource()->getAmountSt(),
            'label' => $this->__('Substituição Tributária')
        ));
        
        $this->getParentBlock()->addTotalBefore($totalST, 'shipping');
        return $this;
    }
}
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Ispshop_Tributos_Model_Sales_Quote_Address_Total_Substituicaotributaria extends Mage_Sales_Model_Quote_Address_Total_Abstract {

    public function __construct() {
        $this->setCode('substituicao_tributaria');
    }

    public function collect(Mage_Sales_Model_Quote_Address $address) {
        parent::collect($address);

        $st = null;

        foreach ($this->_getAddressItems($address) as $item) {
            $st += $item->getValueSt();
        }

        $baseSt = Mage::app()->getStore()->convertPrice($st);

        $this->_addBaseAmount($baseSt);
        $this->_addAmount($st);

        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address) {
        $amount = $address->getQuote()->getAmountSt();
        $addressType = $address->getAddressType();
        if ($addressType == 'billing') {

            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => Mage::helper('ispshop_tributos')
                        ->__('Substituição Tributátia'),
                'value' => $amount
            ));
        }
        return $this;
    }

}

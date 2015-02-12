<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Ispshop_Tributos_Model_Sales_Quote_Address_Total_Icms extends Mage_Sales_Model_Quote_Address_Total_Abstract {

    public function __construct() {
        $this->setCode('icms');
    }

    public function collect(Mage_Sales_Model_Quote_Address $address) {
        parent::collect($address);

        $icms = null;

        foreach ($this->_getAddressItems($address) as $item) {
            if (($item->getValueSt() == '0.00') || ($item->getValueSt() == null)) {
                $icms += $item->getValueIcms();
            }
        }

        $baseIcms = Mage::app()->getStore()->convertPrice($icms);

        $this->_addBaseAmount($baseIcms);
        $this->_addAmount($icms);

        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address) {
        $amount = $address->getQuote()->getAmountIcms();
        $addressType = $address->getAddressType();
        if ($addressType == 'billing') {

            $address->addTotal(array(
                'code' => $this->getCode(),
                'title' => Mage::helper('ispshop_tributos')
                        ->__('ICMS'),
                'value' => $amount
            ));
        }
        return $this;
    }

}

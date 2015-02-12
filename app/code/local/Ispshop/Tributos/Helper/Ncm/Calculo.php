<?php

class Ispshop_Tributos_Helper_Ncm_Calculo extends Mage_Core_Helper_Data {

    public function getSubstituicaoTributaria($destino, $item, $taxas, $quote) {

        $price = $item->getData('price');
        $icms = $item->getData('value_icms');
        $base = $price + $icms + $this->getFretePercentual($quote, $price);
        $base_de_calculo = null;

        if ($destino == 1) {
            $base_de_calculo = $base + ($base * ($this->getMva($item, $taxas) / 100));
        } else {
            $base_de_calculo = $base;
        }

        $st = ($base_de_calculo * (($this->getAliquota($taxas, $quote)) / 100) ) - ($base * ($this->getIcmsRecolhimento($item) / 100));

        return $st;
    }

    public function getValorIcms($item, $taxas, $countryRegionCode) {
        $price = $item->getData('price');
        $base = $price;
        $icms = ($base * ($this->getIcms($taxas, $countryRegionCode) / 100));
        return $icms;
    }

    private function getAliquota($taxas, $quote) {
        $aliquota = null;
        if ($this->isVendaInterna($quote) == 1) {
            $aliquota = $taxas->getData('aliquota_icms_interno');
            if (($aliquota == null ) || ($aliquota == '0.00')) {
                $aliquota = $taxas->getData('aliquota_interna');
            }
        } else {
            $aliquota = $taxas->getData('aliquota_icms_interestadual');
            if (($aliquota == null ) || ($aliquota == '0.00')) {
                $aliquota = $taxas->getData('aliquota_interestadual');
            }
        }
        return $aliquota;
    }

    private function getMva($item, $taxas) {
        $mva = null;

        $isImported = $this->isImported($item);


        if ($isImported == 1) {
            $mva = $taxas->getData('mva_ajustada_4');
            if (($mva == null ) || ($mva == '0.00' )) {
                $mva = $taxas->getData('mva_ajustada_4_origin');
            }
        } else {
            $mva = $taxas->getData('mva_ajustada');
            if (($mva == null ) || ($mva == '0.00' )) {
                $mva = $taxas->getData('mva_ajustada_origin');
            }
        }

        return $mva;
    }

    private function getIcms($taxas, $countryRegionCode) {

        if ($countryRegionCode == Mage::getStoreConfig('ispshop_tributos_config/general/estado', Mage::app()->getStore())) {
            $icms = Mage::getStoreConfig('ispshop_tributos_config/general/aliquota', Mage::app()->getStore());
        } else {
            $icms = $taxas->getData('aliquota_icms_interestadual');
            if (($icms == null ) || ($icms == '0.00' )) {
                $icms = $taxas->getData('aliquota_interestadual');
            }
        }

        return $icms;
    }

    private function getIcmsRecolhimento($item) {
        $icms = null;

        $isImported = $this->isImported($item);

        if ($isImported == 1) {
            $icms = 4;
        } else {
            $icms = 0;
        }

        return $icms;
    }

    private function getFretePercentual($quote, $price) {
        $shippingRates = $quote->getShippingAddress()
                ->collectTotals()
                ->collectShippingRates()
                ->getAllShippingRates();

        $shippingRate = $shippingRates[0];

        $ShippingValue = $shippingRate->getPrice();

        $orderTotal = $quote->getGrandTotal();

        $orderTotalWithoutShippingValue = $orderTotal - $ShippingValue;

        $shippingValueForItem = ($ShippingValue * $price) / $orderTotalWithoutShippingValue;

        return $shippingValueForItem;
    }

    private function isVendaInterna($quote) {
        $regionId = $quote->getShippingAddress()->getRegionId();
        $countryRegion = Mage::getModel('directory/region')->load($regionId);
        $countryRegionCode = $countryRegion->getCode();

        if ($countryRegionCode == 'PR') {
            return 0;
        } else {
            return 1;
        }
    }

    private function isImported($item) {
        $_resource = Mage::getSingleton('catalog/product')->getResource();
        $produto = $item->getProduct();
        $produtId = $produto->getId();
        $isImported = $_resource->getAttributeRawValue($produtId, 'is_imported', Mage::app()->getStore());
        return $isImported;
    }

}

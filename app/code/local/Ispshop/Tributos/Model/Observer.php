<?php

class Ispshop_Tributos_Model_Observer {

    public function cartSaveAfter(Varien_Event_Observer $observer) {
        $quote = $observer->getEvent()->getCart()->getQuote();
        $this->updateNCM($quote);
    }

    private function updateNCM($quote) {

        try {
            $isForConsumption = $quote->getForConsumption();
            $items = $quote->getAllItems();

            $regionId = $quote->getShippingAddress()->getRegionId();
            $countryRegion = Mage::getModel('directory/region')->load($regionId);
            $countryRegionCode = $countryRegion->getCode();
            $stTotal = null;
            $icmsTotal = null;

            foreach ($items as $item) {
                $productId = $item->getProductId();

                $model = Mage::getModel('ispshop_tributos/ncm')->getCollection()
                        ->addFieldToFilter('product_id', $productId)
                        ->addFieldToFilter('region_id', $countryRegionCode);

                $model->join(array('monitoramento' => 'ispshop_tributos/monitoramento'), 'main_table.id_monitoramento_ncm = monitoramento.id', array('id', 'monitoramento.mva_ajustada_4 as mva_ajustada_4_origin', 'monitoramento.mva_ajustada as mva_ajustada_origin', 'monitoramento.mva as mva_origin', 'monitoramento.aliquota_interna', 'monitoramento.aliquota_interestadual'));

                $taxData = $model->getFirstItem();


                $helper = Mage::helper('ispshop_tributos/ncm_calculo');

                $valorICMS = $helper->getValorIcms($item, $taxData, $countryRegionCode, $quote);

                $valorST = $helper->getSubstituicaoTributaria($isForConsumption, $item, $taxData, $quote);

                $icms = $valorICMS * $item->getData('qty');
                $item->setValueIcms($icms);

                $st = $valorST * $item->getData('qty');
                $item->setValueSt($st);

                if ($valorST == 0) {
                    $price_st = ($item->getData('price') + $valorICMS) * $item->getData('qty');
                } else {
                    $price_st = ($item->getData('ajaxprice') + $valorST) * $item->getData('qty');
                }

                $item->setPriceStIcms($price_st);

                $stTotal += $st;
                $icmsTotal += $icms;

                $item->save();
            }

            $quote->setAmountSt($stTotal);
            $quote->setAmountIcms($icmsTotal);

            $quote->collectTotals()->save();

            Mage::getSingleton('checkout/session')->replaceQuote($quote);
        } catch (Exception $e) {
            Mage::logException($e);
            $result['success'] = false;
            $result['error'][] = $this->__('Não foi possível setar o destino dos produtos.');
        }
    }

}

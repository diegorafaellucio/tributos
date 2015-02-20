<?php

/**
 * @category   Ispshop
 * @package    Ispshop_Tributos
 */
class Ispshop_Tributos_Customer_CheckoutController extends Mage_Checkout_Controller_Action {

    protected function _goBack() {
        $returnUrl = $this->getRequest()->getParam('return_url');
        if ($returnUrl) {

            if (!$this->_isUrlInternal($returnUrl)) {
                throw new Mage_Exception('External urls redirect to "' . $returnUrl . '" denied!');
            }

            $this->_getSession()->getMessages(true);
            $this->getResponse()->setRedirect($returnUrl);
        } elseif (!Mage::getStoreConfig('checkout/cart/redirect_to_cart') && !$this->getRequest()->getParam('in_cart') && $backUrl = $this->_getRefererUrl()
        ) {
            $this->getResponse()->setRedirect($backUrl);
        } else {
            if (($this->getRequest()->getActionName() == 'add') && !$this->getRequest()->getParam('in_cart')) {
                $this->_getSession()->setContinueShoppingUrl($this->_getRefererUrl());
            }
            $this->_redirect('checkout/cart');
        }
        return $this;
    }

    public function saveConsumptionMethodAction() {

        try {
            if ($this->getRequest()->isPost()) {
                $data = $this->getRequest()->getPost('consumption_method', array());
                $this->getOnepage()->getQuote()->setForConsumption($data);
                $items = $this->getOnepage()->getQuote()->getAllItems();

                $regionId = $this->getOnepage()->getQuote()->getShippingAddress()->getRegionId();
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

                    $valorICMS = $helper->getValorIcms($item, $taxData, $countryRegionCode, $this->getOnepage()->getQuote());

                    $valorST = $helper->getSubstituicaoTributaria($data, $item, $taxData, $this->getOnepage()->getQuote());

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

                $this->getOnepage()->getQuote()->setAmountSt($stTotal);
                $this->getOnepage()->getQuote()->setAmountIcms($icmsTotal);

                $this->getOnepage()->getQuote()->collectTotals()->save();

                $quote = Mage::getModel('sales/quote')->setStoreId(Mage::app()->getStore()->getId());
                $quote->load($this->getOnepage()->getQuote()->getId());

                Mage::getSingleton('checkout/session')->replaceQuote($quote);
            }
        } catch (Exception $e) {
            Mage::logException($e);
            $result['success'] = false;
            $result['error'][] = $this->__('Não foi possível setar o destino dos produtos.');
        }
        $this->_goBack();
    }

    /**
     * @return Mage_Checkout_Model_Type_Onepage
     */
    public function getOnepage() {
        return Mage::getSingleton('checkout/type_onepage');
    }

}

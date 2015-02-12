<?php

/**
 * @category   Ispshop
 * @package    Ispshop_Tributos
 */
class Ispshop_Tributos_Customer_AjaxController extends Mage_Checkout_Controller_Action {

    /**
     * @return AW_Onestepcheckout_AjaxController|Mage_Core_Controller_Front_Action
     */
    public function preDispatch() {
        parent::preDispatch();
        $this->_preDispatchValidateCustomer();

        $checkoutSessionQuote = Mage::getSingleton('checkout/session')->getQuote();
        if ($checkoutSessionQuote->getIsMultiShipping()) {
            $checkoutSessionQuote->setIsMultiShipping(false);
            $checkoutSessionQuote->removeAllAddresses();
        }
        return $this;
    }

    public function saveConsumptionMethodAction() {
        if ($this->_expireAjax()) {
            return;
        }
        $result = array(
            'success' => true,
            'messages' => array(),
            'blocks' => array(),
            'grand_total' => ""
        );
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

                    $model->join(array('monitoramento' => 'ispshop_tributos/monitoramento'), 'main_table.id_monitoramento_ncm = monitoramento.id', array('id', 'monitoramento.mva_ajustada_4 as mva_ajustada_4_origin', 'monitoramento.mva_ajustada as mva_ajustada_origin', 'monitoramento.aliquota_interna', 'monitoramento.aliquota_interestadual'));

                    $taxData = $model->getFirstItem();


                    $helper = Mage::helper('ispshop_tributos/ncm_calculo');

                    $valorICMS = $helper->getValorIcms($item, $taxData, $countryRegionCode);

                    $valorST = $helper->getSubstituicaoTributaria($data, $item, $taxData, $this->getOnepage()->getQuote());

                    $icms = $valorICMS * $item->getData('qty');
                    $item->setValueIcms($icms);

                    $st = $valorST * $item->getData('qty');
                    $item->setValueSt($st);

                    if ($valorST == 0) {
                        $price_st = ($item->getData('price') + $valorICMS) * $item->getData('qty');
                    } else {
                        $price_st = ($item->getData('price') + $valorST) * $item->getData('qty');
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

                $result['blocks'] = $this->getUpdater()->getBlocks();
                $result['grand_total'] = Mage::helper('aw_onestepcheckout')->getGrandTotal($this->getOnepage()->getQuote());
            } else {
                $result['success'] = false;
                $result['messages'][] = $this->__('Por favor informe o destino dos produtos.');
            }
        } catch (Exception $e) {
            Mage::logException($e);
            $result['success'] = false;
            $result['error'][] = $this->__('Não foi possível setar o destino dos produtos.');
        }
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * @return Mage_Checkout_Model_Type_Onepage
     */
    public function getOnepage() {
        return Mage::getSingleton('checkout/type_onepage');
    }

    /**
     * @return AW_Onestepcheckout_Model_Updater
     */
    public function getUpdater() {
        return Mage::getSingleton('ispshop_tributos/updater');
    }

    /**
     * Check can page show for unregistered users
     *
     * @return boolean
     */
    protected function _canShowForUnregisteredUsers() {
        //TODO: show login block only for unregistered
        return Mage::getSingleton('customer/session')->isLoggedIn() || Mage::helper('checkout')->isAllowedGuestCheckout($this->getOnepage()->getQuote()) || !Mage::helper('checkout')->isCustomerMustBeLogged();
    }

    /**
     * @return AW_Onestepcheckout_AjaxController
     */
    protected function _ajaxRedirectResponse() {
        $this->getResponse()
                ->setHeader('HTTP/1.1', '403 Session Expired')
                ->setHeader('Login-Required', 'true')
                ->sendResponse();
        return $this;
    }

    /**
     * @return bool
     */
    protected function _expireAjax() {
        if (!$this->getOnepage()->getQuote()->hasItems() || $this->getOnepage()->getQuote()->getHasError() || $this->getOnepage()->getQuote()->getIsMultiShipping()) {
            $this->_ajaxRedirectResponse();
            return true;
        }
        if (Mage::getSingleton('checkout/session')->getCartWasUpdated(true)) {
            $this->_ajaxRedirectResponse();
            return true;
        }
        return false;
    }

}

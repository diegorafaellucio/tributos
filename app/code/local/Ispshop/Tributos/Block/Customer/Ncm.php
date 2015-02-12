<?php

class Ispshop_Tributos_Block_Customer_Ncm extends Mage_Core_Block_Template {

    public function getBlockNumber($isIncrementNeeded = true) {
        return Mage::helper('aw_onestepcheckout')->getBlockNumber($isIncrementNeeded);
    }

    public function getSaveConsumptiontUrl() {
        return Mage::getUrl('ispshop_tributos/customer_ajax/saveConsumptionMethod');
    }

}

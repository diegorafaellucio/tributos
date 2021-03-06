<?php
/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/AW-LICENSE.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento community edition
 * aheadWorks does not guarantee correct work of this extension
 * on any other Magento edition except Magento community edition.
 * aheadWorks does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   AW
 * @package    AW_Onestepcheckout
 * @version    1.2.0
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */


class Ispshop_Tributos_Block_Customer_Onestep extends Mage_Checkout_Block_Onepage_Abstract
{
    public function getGrandTotal()
    {
        return Mage::helper('aw_onestepcheckout')->getGrandTotal($this->getQuote());
    }

    public function getPlaceOrderUrl()
    {
        return Mage::getUrl('onestepcheckout/ajax/placeOrder', array('_secure'=>true));
    }

    public function getBlockMap()
    {
        $updater = Mage::getModel('ispshop_tributos/updater');
        $result = array();
        foreach($updater->getMap() as $action => $blocks) {
            $result[$action] = array_keys($blocks);
        }
        return $result;
    }

    public function getBlockNumber($isIncrementNeeded = true)
    {
        return Mage::helper('aw_onestepcheckout')->getBlockNumber($isIncrementNeeded);
    }
}
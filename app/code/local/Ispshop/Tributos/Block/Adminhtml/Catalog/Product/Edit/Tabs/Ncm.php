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
 * @package    AW_Helpdeskultimate
 * @version    2.10.5
 * @copyright  Copyright (c) 2010-2012 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/AW-LICENSE.txt
 */
class Ispshop_Tributos_Block_Adminhtml_Catalog_Product_Edit_Tabs_Ncm extends Mage_Adminhtml_Block_Widget implements Mage_Adminhtml_Block_Widget_Tab_Interface {

    /**
     * Reference to product objects that is being edited
     *
     * @var Mage_Catalog_Model_Product
     */
    protected $_product = null;
    protected $_config = null;

    /**
     * Get tab label
     *
     * @return string
     */
    public function getTabLabel() {
        return Mage::helper('ispshop_tributos')->__('Controle Substituição Tributária');
    }

    public function getTabTitle() {
        return Mage::helper('ispshop_tributos')->__('Controle Substituição Tributária');
    }

    public function canShowTab() {
        return true;
    }

    /**
     * Check if tab is hidden
     *
     * @return boolean
     */
    public function isHidden() {
        return false;
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml() {
        $id = $this->getRequest()->getParam('id');
        $collection = Mage::getModel('ispshop_tributos/ncm')->getCollection()->addFieldToFilter('product_id', $id);
        $select = $collection->getSelect();
        $ncmNewUrl = $this->getUrl('/ncm/new', array('product_id' => $id));
        $ncmUpdateUrl = $this->getUrl('/ncm/update', array('product_id' => $id));
        $buttonAdd = $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setClass('add')
                ->setType('button')
                ->setOnClick('window.location.href=\'' . $ncmNewUrl . '\'')
                ->setLabel($this->__('Cadastrar Substituição Tributária'));
        $buttonAdd->setStyle('margin:10px;float:none;clear:both;');
        $buttonUpdate = $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setClass('add')
                ->setType('button')
                ->setOnClick('window.location.href=\'' . $ncmUpdateUrl . '\'')
                ->setLabel($this->__('Atualizar dados de Substituição Tributária'));
        $buttonUpdate->setStyle('margin:10px;float:none;clear:both;');
        $grid = $this->getLayout()->createBlock('ispshop_tributos/adminhtml_ncm_grid');
        $grid->setDefaultFilter(array('order_id' => $id));
        $grid->setFilterVisibility(false);
        $grid->setPagerVisibility(0);
        $grid->setUserMode();
        $grid->setOrderMode(1);
        $grid->setStoreSwitcherVisibility(false);
        $grid->setOnePage();

        $canInsertSubstituicao = Mage::getSingleton('admin/session')->isAllowed('admin/sales/ispshop_tributos_ncm/pode_cadastrar');
        $canDeleteSubstituicao = Mage::getSingleton('admin/session')->isAllowed('admin/sales/ispshop_tributos_ncm/pode_excluir');

        if ($canInsertSubstituicao && $canDeleteSubstituicao) {
            if ($collection->getSize() > 0) {
                return $buttonUpdate->toHtml() . $grid->toHtml();
            } else {
                return $buttonAdd->toHtml() . $grid->toHtml();
            }
        } else if ($canInsertSubstituicao) {
            if (!$collection->getSize() > 0) {
                return $buttonAdd->toHtml() . $grid->toHtml();
            }
        } else if ($canDeleteSubstituicao) {
            if ($collection->getSize() > 0) {
                return $buttonUpdate->toHtml() . $grid->toHtml();
            }
        } else {
            return $grid->toHtml();
        }
    }

}

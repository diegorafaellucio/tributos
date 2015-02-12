<?php

class Ispshop_Tributos_Adminhtml_LogController extends Mage_Adminhtml_Controller_Action {

    public function indexAction() {
        // Let's call our initAction method which will set some basic params for each action
        $this->_initAction()
                ->renderLayout();
    }


    /**
     * Initialize action
     *
     * Here, we set the breadcrumbs and the active menu
     *
     * @return Mage_Adminhtml_Controller_Action
     */
    protected function _initAction() {
        $this->loadLayout()
                // Make the active menu match the menu config nodes (without 'children' inbetween)
                ->_setActiveMenu('ispshop_tributos/ispshop_triobutos_log')
                ->_title($this->__('Substituição Tributária'))->_title($this->__('Logs'))
                ->_addBreadcrumb($this->__('Substituição Tributária'), $this->__('Substituição Tributária'))
                ->_addBreadcrumb($this->__('Logs'), $this->__('Logs'));

        return $this;
    }

    /**
     * Check currently called action by permissions for current user
     *
     * @return bool
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('sales/ispshop_tributos_ncm');
    }

}

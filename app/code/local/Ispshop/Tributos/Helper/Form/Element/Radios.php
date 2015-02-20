<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Varien
 * @package    Varien_Data
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Radio buttons collection
 *
 * @category   Varien
 * @package    Varien_Data
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Ispshop_Tributos_Helper_Form_Element_Radios extends Varien_Data_Form_Element_Abstract {

    public function __construct($attributes = array()) {
        parent::__construct($attributes);
        $this->setType('ispshop-radios');
    }

    public function getSeparator() {
        $separator = $this->getData('separator');
        if (is_null($separator)) {
            $separator = '&nbsp;';
        }
        return $separator;
    }

    public function getElementHtml() {

        $html = '<style type="text/css">';

        $html .= '.tg  {border-collapse:collapse;border-spacing:0; width:100%;}';
        $html .= '.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}';
        $html .= '.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}';
        $html .= '</style>';
        $html .= '<table class="tg">';

        $html .= '<tr>';
        $html .= '<th class="tg-031e"></th>';
        $html .= '<th class="tg-031e">NCM</th>';
        $html .= '<th class="tg-031e">NCM NORMA</th>';
        $html .= '<th class="tg-031e">Descrição</th>';
        $html .= '<th class="tg-031e">Descrição Norma</th>';
        $html .= '<th class="tg-031e">Aliq. Externa</th>';
        $html .= '<th class="tg-031e">Aliq. Interna</th>';
        $html .= '<th class="tg-031e">MVA</th>';
        $html .= '<th class="tg-031e">MVA Ajustada</th>';
        $html .= '<th class="tg-031e">MVA Ajustada 4%</th>';
        $html .= '<th class="tg-031e">IPI</th>';
        $html .= '</tr>';

        $value = $this->getValue();
        if ($values = $this->getValues()) {
            foreach ($values as $option) {
                $html.= '<tr>';
                $html.= $this->_optionToHtml($option, $value);
                $html.= '</tr>';
            }
        }
        $html .= '</table>';
        $html.= $this->getAfterElementHtml();
        return $html;
    }

    protected function _optionToHtml($option, $selected) {

        $html = '<td  class="tg-031e">';
        $html .= '<input type="radio"' . $this->serialize(array('name', 'class', 'style'));
        if (is_array($option)) {
            $html.= 'value="' . $this->_escape($option['value']) . '"  id="' . $this->getHtmlId() . $option['value'] . '"';
            if ($option['value'] == $selected) {
                $html.= ' checked="checked"';
            }
            $html.= ' />';
            $html.= ' </td>';
            foreach ($option['label'] as $key => $value) {
                if ($key >= 4) {
                    $html.= '<td class="tg-031e">';
                    $html.= str_replace('.',',',$value);
                    $html.= '</td>';
                } else {
                    $html.= '<td class="tg-031e">';
                    $html.= $value;
                    $html.= '</td>';
                }
            }
        }

        return $html;
    }

}

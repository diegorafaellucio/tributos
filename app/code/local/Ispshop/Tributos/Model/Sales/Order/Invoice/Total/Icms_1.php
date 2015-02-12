<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Ispshop_Tributos_Model_Sales_Order_Invoice_Total_Icms extends Mage_Sales_Model_Order_Invoice_Total_Abstract {

    public function collect(Mage_Sales_Model_Order_Invoice $invoice) {
        $invoice->setAmountIcms(0);

        // Get any amount we've invoiced already
        $prevInvoiceAmounICMS = 0;


        foreach ($invoice->getOrder()->getInvoiceCollection() as $prevInvoice) {
            if ($prevInvoice->getAmountICMS() && !$prevInvoice->isCanceled()) {
                $prevInvoiceAmounICMS += $prevInvoice->getAmountIcms();
            }
        }

        $AmountICMS = 0;
        $invoiceIsLast = true;
        foreach ($invoice->getAllItems() as $item) {

            if (($item->getValueSt() == '0.00') || ($item->getValueSt() == 0) || ($item->getValueSt() == null)) {

                $orderItem = $item->getOrderItem();
                if ($orderItem->isDummy()) {
                    continue;
                }

                if (!$item->isLast()) {
                    $invoiceIsLast = false;
                }

                $orderItemQty = $orderItem->getQtyOrdered();
                $qtyItemToInvoice = $item->getQty();
                // If invoicing the last quantity of this item, invoice the surcharge
                if (($orderItemPrevInvoiced + $item->getQty()) == $orderItemQty) {
                    $AmountICMS += $orderItem->getValueIcms();
                } else {
                    $AmountICMS += ($orderItem->getValueIcms() / $orderItemQty) * $qtyItemToInvoice;
                }
            }
        }

        if (($item->getValueSt() == '0.00') || ($item->getValueSt() == null)) {

            // Just to be safe, if this is the last invoice, override with the full remaining surcharge amount
            if ($invoiceIsLast && ($prevInvoiceAmounICMS != 0)) {
                $AmountICMS = $invoice->getOrder()->getAmountIcms() - $prevInvoiceAmounICMS;
            }
        }

        $invoice->setAmountIcms($AmountICMS);

        $invoice->setGrandTotal($invoice->getGrandTotal() + $AmountICMS);
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $AmountICMS);

        return $this;
    }

}

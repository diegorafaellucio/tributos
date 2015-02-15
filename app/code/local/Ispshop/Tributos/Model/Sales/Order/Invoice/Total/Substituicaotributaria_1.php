<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Ispshop_Tributos_Model_Sales_Order_Invoice_Total_Substituicaotributaria extends Mage_Sales_Model_Order_Invoice_Total_Abstract {

    public function collect(Mage_Sales_Model_Order_Invoice $invoice) {
        $invoice->setAmountSt(0);

        // Get any amount we've invoiced already
        $prevInvoiceAmountSt = 0;
        foreach ($invoice->getOrder()->getInvoiceCollection() as $prevInvoice) {
            if ($prevInvoice->getAmountSt() && !$prevInvoice->isCanceled()) {
                $prevInvoiceAmountSt += $prevInvoice->getAmountSt();
            }
        }

        $AmountSt = 0;
        $invoiceIsLast = true;
        foreach ($invoice->getAllItems() as $item) {
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
            $AmountSt += ($orderItem->getValueSt() / $orderItemQty) * $qtyItemToInvoice;
        }
        // Just to be safe, if this is the last invoice, override with the full remaining surcharge amount
        if ($invoiceIsLast && ($prevInvoiceAmountSt != 0)) {
            $AmountSt = $invoice->getOrder()->getAmountSt() - $prevInvoiceAmountSt;
        }

        $invoice->setAmountSt($AmountSt);

        $invoice->setGrandTotal($invoice->getGrandTotal() + $AmountSt);
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $AmountSt);

        return $this;
    }

}

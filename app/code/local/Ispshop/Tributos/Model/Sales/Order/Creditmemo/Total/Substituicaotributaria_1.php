<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Ispshop_Tributos_Model_Sales_Order_Creditmemo_Total_Substituicaotributaria extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract {

    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo) {
        $creditmemo->setAmountSt(0);

        // Just to be safe, get any amount we've refunded already (shouldn't be possible!)
        // so we can make sure we don't refund more than was charged
        $prevCreditmemoAmountSt = 0;
        foreach ($creditmemo->getOrder()->getCreditmemosCollection() as $prevCreditmemo) {
            if ($prevCreditmemo->getAmountSt() && ($prevCreditmemo->getState() != Mage_Sales_Model_Order_Creditmemo::STATE_CANCELED)) {
                $prevCreditmemoAmountSt += $prevCreditmemo->getAmountSt();
            }
        }

        $AmountSt = 0;
        $creditmemoIsLast = true;
        foreach ($creditmemo->getAllItems() as $item) {
            $orderItem = $item->getOrderItem();
            if ($orderItem->isDummy()) {
                continue;
            }

            if (!$item->isLast()) {
                $creditmemoIsLast = false;
            }

            $qtyToRefound = $item->getQty();
            $orderItemQty = $orderItem->getQtyOrdered();


            $AmountSt += ($orderItem->getValueSt() / $orderItemQty) * $qtyToRefound;


            $item->setValueSt(($orderItem->getValueSt() / $orderItemQty) * $qtyToRefound);
            $item->setPriceStIcms(((($orderItem->getPriceStIcms() - (($orderItem->getValueSt() / $orderItemQty) * $qtyToRefound)) / $orderItemQty) * $qtyToRefound) + (($orderItem->getValueSt() / $orderItemQty) * $qtyToRefound));
        }

        if ($creditmemoIsLast && ($prevCreditmemoAmountSt != 0)) {
            $AmountSt = $creditmemo->getOrder()->getAmountSt() - $prevCreditmemoAmountSt;
        }

        // Only if the entire order has been refunded, refund the surcharge
        $creditmemo->setAmountSt($AmountSt);

        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $AmountSt);
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $AmountSt);

        return $this;
    }

}

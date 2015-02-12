<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Ispshop_Tributos_Model_Sales_Order_Creditmemo_Total_Icms extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract {

    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo) {
        if (($creditmemo->getAmountSt() == '0.00') || ($creditmemo->getAmountSt() == 0) || ($creditmemo->getAmountSt() == null )) {

            $creditmemo->setAmountIcms(0);

            // Just to be safe, get any amount we've refunded already (shouldn't be possible!)
            // so we can make sure we don't refund more than was charged
            $prevCreditmemoAmountIcms = 0;
            foreach ($creditmemo->getOrder()->getCreditmemosCollection() as $prevCreditmemo) {
                if ($prevCreditmemo->getAmountIcms() && ($prevCreditmemo->getState() != Mage_Sales_Model_Order_Creditmemo::STATE_CANCELED)) {
                    $prevCreditmemoAmountIcms += $prevCreditmemo->getAmountIcms();
                }
            }

            $AmountIcms = 0;
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


                $AmountIcms += ($orderItem->getValueIcms() / $orderItemQty) * $qtyToRefound;


                $item->setValueIcms(($orderItem->getValueIcms() / $orderItemQty) * $qtyToRefound);
                $item->setPriceStIcms(((($orderItem->getPriceStIcms() - (($orderItem->getValueIcms() / $orderItemQty) * $qtyToRefound))/ $orderItemQty) * $qtyToRefound) + (($orderItem->getValueIcms() / $orderItemQty) * $qtyToRefound));
            }

            if ($creditmemoIsLast && ($prevCreditmemoAmountIcms != 0)) {
                $AmountIcms = $creditmemo->getOrder()->getAmountIcms() - $prevCreditmemoAmountIcms;
            }

            // Only if the entire order has been refunded, refund the surcharge
            $creditmemo->setAmountIcms($AmountIcms);

            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $AmountIcms);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $AmountIcms);
        }

        return $this;
    }

}

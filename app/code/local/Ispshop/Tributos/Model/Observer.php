<?php
class Ispshop_Tributos_Model_Observer{
    /**
     * This function is called just before $quote object get stored to database.
     * Here, from POST data, we capture our custom field and put it in the quote object
     * @param unknown_type $evt
     */
    public function saveQuoteBefore($evt){
        $quote = $evt->getQuote();
        $post = Mage::app()->getFrontController()->getRequest()->getPost();
        if(isset($post['custom']['ssn'])){
            $var = $post['custom']['ssn'];
            $quote->setSsn($var);
        }
    }
}
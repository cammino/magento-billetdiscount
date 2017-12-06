<?php
/**
 * Data
 *
 * This file is contains some functions to help other methods in module
 *
 * @category   Cammino
 * @package    Billetdiscount
 * @author     Cammino Digital <contato@cammino.com.br>
 */

class Cammino_Billetdiscount_Helper_Data extends Mage_Core_Helper_Abstract
{

	public function getRuleId(){
		return Mage::getStoreConfig('catalog/billetdiscount/ruleid');
	}

	// get value of billet with discount
	public function getBilletTotal($price, $shipping = 0){
		$billetDiscount = $this->getPercentDiscount();

		if (!empty($billetDiscount)) {
	        $billetDiscountVal = 1;
			$billetDiscountVal = ((100 - floatval($billetDiscount)) / 100);
			$newPrice = bcdiv(((($price - $shipping) * $billetDiscountVal) + $shipping), 1, 2);
			return $price == $newPrice ? 0 : $this->currency($newPrice);
		}
		else 
			return '';
	}

	private function getQuote(){
        return $this->_session->getQuote();
    }

    private function currency($price){
        return Mage::helper('core')->currency($price, true, false);
    }

    // get rule of billet discount
    public function getRuleDiscount() {
    	$ruleId = $this->getRuleId();	
		$billetDiscountRule = Mage::getModel('salesrule/rule')->load($ruleId);
		return $billetDiscountRule;
    }
    // get % of discount on billets
    public function getPercentDiscount(){
    	$billetDiscountRule = $this->getRuleDiscount();
    	if ($billetDiscountRule['is_active']) {
			return floatval($billetDiscountRule["discount_amount"]);
		}
		else
			return '';
    }

    // return total value with billet discounts verifying if the 
    // value passed as arg to this function is with discount
    public function getBilletTotalCheckout($price) {
    	$quote = Mage::getSingleton('checkout/session')->getQuote();
    	$quoteData = $quote->getData(); 
    	$grandTotal = $quoteData['grand_total'];       
    	$billetDiscountRule = $this->getRuleDiscount();

    	$shippingAmount = $quote->getShippingAddress()->getShippingAmount();
    	$billetDiscount = $this->getPercentDiscount();

		if (in_array(intval($billetDiscountRule->getId()), explode(',', $quote->getAppliedRuleIds()))) {
    		return $this->currency($grandTotal);
    	}
    	else {
    		return $this->getBilletTotal($price, $shippingAmount);
    	}
	    
    }
}
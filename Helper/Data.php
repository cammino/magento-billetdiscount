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

	public function getBilletTotal($price){
		$ruleId = $this->getRuleId();
		
		$billetDiscountRule = Mage::getModel('salesrule/rule')->load($ruleId);
		if ($billetDiscountRule['is_active']) {
	        $billetDiscountVal = 1;
			$billetDiscountVal = ((100 - floatval($billetDiscountRule["discount_amount"])) / 100);
			$newPrice = $price * $billetDiscountVal;

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
}
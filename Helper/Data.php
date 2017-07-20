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

	public function getPriceWithDiscount($product){
		$ruleId = $this->getRuleId();
		$data = $this->getQuoteData($product);
		$grandTotal = $data['grand_total'];
		
		$rule = Mage::getModel('salesrule/rule')->load($ruleId);
		$discount = $rule["discount_amount"] / 100;

		$finalPrice = $grandTotal - ($grandTotal * $discount);
		
		return $finalPrice;
	}

	public function getQuoteData($product){
		$storeId = Mage::app()->getStore()->getStoreId();
		
		$quote = Mage::getModel('sales/quote')->setStoreId($storeId);
    	
		$stockItem = Mage::getModel('cataloginventory/stock_item');
    	$stockItem->assignProduct($product)
      		->setData('stock_id', 1)
      		->setData('store_id', $storeId);

    	$stockItem->setUseConfigManageStock(false);
    	$stockItem->setManageStock(false);
    	
    	$quote->addProduct($product, 1);
    	$quote->getShippingAddress()->setCountryId('BR');
    	$quote->collectTotals();

    	return $quote;
	}

	public function getDiscount($product){

	}
}
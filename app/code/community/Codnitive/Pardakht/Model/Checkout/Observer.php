<?php
/**
 * CODNITIVE
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE_EULA.html.
 * It is also available through the world-wide-web at this URL:
 * http://www.codnitive.com/en/terms-of-service-softwares/
 * http://www.codnitive.com/fa/terms-of-service-softwares/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @category   Codnitive
 * @package    Codnitive_Pardakht
 * @author     Hassan Barza <support@codnitive.com>
 * @copyright  Copyright (c) 2012 CODNITIVE Co. (http://www.codnitive.com)
 * @license    http://www.codnitive.com/en/terms-of-service-softwares/ End User License Agreement (EULA 1.0)
 */

class Codnitive_Pardakht_Model_Checkout_Observer extends Mage_Core_Model_Abstract
{

	protected $_config;
	protected $_customerId;

	protected function _getSession()
	{
		return Mage::getSingleton('checkout/session');
	}

	public function __construct()
	{
		parent::__construct();
		$this->_config     = Mage::getModel('pardakht/config');
	}

	public function sendOrderToPardakht(Varien_Event_Observer $observer)
	{
		if (!$this->_config->isActive()) {
			return;
		}

		$order       = Mage::getModel('sales/order')->load($this->_getSession()->getLastOrderId());
		$paymentType = $order->getPayment()->getMethodInstance()->getCode();
		$paymentTypes = Mage::helper('pardakht')->getPaymentTypes();
		unset($paymentTypes['pardakhtonline']);

		if (!array_key_exists($paymentType, $paymentTypes)) {
			return;
		}

		$customer = Mage::getModel('customer/customer')->load(
			$order->getShippingAddress()->getCustomerId());

		$data     = array(
			'customer' => $customer,
			'order'    => $order
		);

		$result    = Mage::getModel('pardakht/api')->sendOrderToPardakht($data);
		/*$resultStr = */Mage::getModel('pardakht/setorder')->saveResult($result, $order->getId());

		/*$session   = Mage::getSingleton('catalog/session');
		$errors    = Mage::helper('pardakht')->getErrorsList();
		if (in_array($resultStr, $errors)) {
			$session->addError($resultStr);
		}
		else {
			$session->addSuccess(Mage::helper('pardakht')->__('Your Pardakht Order #: %s', $resultStr));
		}*/
	}

//	public function saveUsedCreditInOrder(Varien_Event_Observer $observer)
//	{
//		$usedCredit = $this->_requestedCreditAmount;
//		if (is_null($usedCredit) || $usedCredit <= 0) {
//			return;
//		}
//
//		if ('submit_order' == $this->_config->getManageCredit()) {
//			$lastOrderId       = $this->_getSession()->getLastOrderId();
//			$order             = Mage::getModel('sales/order')->load($lastOrderId);
//			$this->_customerId = $order->getBillingAddress()->getCustomerId();
//
//			$creditAccount     = $this->_getCredit()->load($this->_customerId, 'customer_id');
//			$hasAccount        = $creditAccount->getId() && count($creditAccount->getId()) === 1;
//			if ($hasAccount) {
//				$currentCredit = $creditAccount->getCreditAmount();
//				$updatedCredit = $currentCredit - $usedCredit;
//				$creditAccount->setCreditAmount($updatedCredit)->save();
//
//				$this->_logUsedCredit($creditAccount, $order);
//			}
//
//			$creditId      = $creditAccount->getId();
//			$creditInOrder = Mage::getModel('creditmoney/inorder');
//			$creditInOrder->setCreditId($creditId)
//					->setOrderId($lastOrderId)
//					->setUsedAmount($usedCredit)
//					->save();
//		}
//	}
//
//	protected function _logUsedCredit($credit, $order)
//	{
//		$customer = Mage::getModel('customer/customer')->load($this->_customerId);
//		$changeAmount = '-' . number_format($this->_requestedCreditAmount, 0);
//
//		$log = Mage::getModel('creditmoney/log');
//		$log->setCreditId($credit->getId())
//				->setRequestId($order->getIncrementId())
//				->setChangeAmount((string)$changeAmount)
//				->setCreatedAt($order->getCreatedAt())
//				->setState(Codnitive_CreditMoney_Model_Config::LOG_STATE_USED_CREDIT)
//				->setCreditAmount($credit->getCreditAmount())
//				->setCustomerName($customer->getName())
//				->save();
//	}

}
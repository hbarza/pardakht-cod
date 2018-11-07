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

class Codnitive_Pardakht_Block_Payment_Info extends Mage_Payment_Block_Info
{

	protected $_helper;

	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('codnitive/pardakht/payment/info.phtml');
	}

	public function getMageHelper()
	{
		if (!$this->_helper) {
			$this->_helper = Mage::helper('pardakht');
		}
		return $this->_helper;
	}

	public function getPaymentInfo($orderId)
	{
		return Mage::getModel('pardakht/setorder')->loadByOrderId($orderId);
	}

	public function isSetOrderSuccess($description)
	{
		$errors = $this->helper('pardakht')->getErrorsList();
		return !array_key_exists($description, $errors);
	}

	public function getPaymentDescription($orderId)
	{
		$paymentInfo = $this->getPaymentInfo($orderId);
		$message = 'Order has been successfully registered on Pardakht.';
		if (!$this->isSetOrderSuccess($paymentInfo->getDescription())) {
			$message = $this->helper('pardakht')->__('Error: %s', $paymentInfo->getDescription());
		}

		return $message;
	}

	public function toPdf()
	{
		$this->setTemplate('codnitive/pardakht/payment/pdf/info.phtml');
		return $this->toHtml();
	}

}
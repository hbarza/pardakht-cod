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

/**
 * Cash on delivery payment method model
 */
class Codnitive_Pardakht_Model_Method_Cashondelivery extends Mage_Payment_Model_Method_Abstract
{

	/**
	 * Payment method code
	 *
	 * @var string
	 */
	protected $_code  = 'pardakht';

//	/**
//	 * Cash On Delivery payment block paths
//	 *
//	 * @var string
//	 */
//	protected $_formBlockType = 'payment/form_cashondelivery';
	protected $_infoBlockType = 'pardakht/payment_info';
//
//	/**
//	 * Get instructions text from config
//	 *
//	 * @return string
//	 */
//	public function getInstructions()
//	{
//		return trim($this->getConfigData('instructions'));
//	}

	public function isAvailable($quote = null)
	{
		$shippingMethod = Mage::getSingleton('checkout/session')->getQuote()
			->getShippingAddress()->getShippingMethod();

		$condition = !Mage::getModel('pardakht/config')->isActivePayment()
			|| (int)substr($shippingMethod, -1, 1) !== 1;
		if ($condition) {
			return false;
		}

		return parent::isAvailable($quote);
	}

}

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
class Codnitive_Pardakht_Model_Method_Online extends Mage_Payment_Model_Method_Abstract
{

	/**
	 * Payment method code
	 *
	 * @var string
	 */
	protected $_code  = 'pardakhtonline';
	protected $_formBlockType  = 'pardakht/payment_checkout_form';

//	/**
//	 * Cash On Delivery payment block paths
//	 *
//	 * @var string
//	 */
//	protected $_formBlockType = 'payment/form_cashondelivery';
	protected $_infoBlockType = 'pardakht/payment_info';

	protected $_isGateway               = true;
	protected $_canAuthorize            = false;
	protected $_canCapture              = false;
	protected $_canCapturePartial       = false;
	protected $_canRefund               = false;
	protected $_canRefundInvoicePartial = false;
	protected $_canVoid                 = false;
	protected $_canUseInternal          = false;
	protected $_canUseCheckout          = true;
	protected $_canUseForMultishipping  = false;
	protected $_isInitializeNeeded      = false;

	/*protected $_order;*/

	protected $_canOrder                    = false;
	protected $_canFetchTransactionInfo     = false;
	protected $_canReviewPayment            = false;
	protected $_canCreateBillingAgreement   = false;
	protected $_canManageRecurringProfiles  = false;

	/*protected $_config;

	public function __construct()
	{
		parent::__construct();
		$this->_config = $this->getConfig();
	}*/
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

	/*public function getOrder()
	{
		if (!$this->_order) {
			$this->_order = $this->getInfoInstance()->getOrder();
		}
		return $this->_order;
	}*/

	public function getOrderPlaceRedirectUrl()
	{
		return Mage::getUrl('pardakht/processing/redirect', array('_secure' => false));
	}

	protected function getResponseUrl()
	{
		return Mage::getUrl('pardakht/processing/response');
	}

	public function isAvailable($quote = null)
	{
		$shippingMethod = Mage::getSingleton('checkout/session')->getQuote()
			->getShippingAddress()->getShippingMethod();

		$condition = !Mage::getModel('pardakht/config')->isActiveOnlinePayment()
			|| (int)substr($shippingMethod, -1, 1) !== 0;
		if ($condition) {
			return false;
		}

		return parent::isAvailable($quote);
	}

}

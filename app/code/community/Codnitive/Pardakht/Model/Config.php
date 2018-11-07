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

class Codnitive_Pardakht_Model_Config
{

	const PATH_NAMESPACE                = 'codnitivepaymentmethods';
	const EXTENSION_NAMESPACE           = 'pardakht';
	const SHIPPING_NAMESPACE            = 'carriers';
	const PAYMENT_NAMESPACE             = 'payment';
	const ONLINE_PAYMENT_NAMESPACE      = 'pardakhtonline';
	const MONEY_ORDER_PAYMENT_NAMESPACE = 'pardakhtmoneyorder';

	const EXTENSION_NAME    = 'Pardakht COD';
	const EXTENSION_VERSION = '1.0.00';
	const EXTENSION_EDITION = 'Basic';

	public static function getNamespace()
	{
		return self::PATH_NAMESPACE . '/' . self::EXTENSION_NAMESPACE . '/';
	}

	public function getExtensionName()
	{
		return self::EXTENSION_NAME;
	}

	public function getExtensionVersion()
	{
		return self::EXTENSION_VERSION;
	}

	public function getExtensionEdition()
	{
		return self::EXTENSION_EDITION;
	}

	public static function getShippingNamespace()
	{
		return self::SHIPPING_NAMESPACE . '/' . self::EXTENSION_NAMESPACE . '/';
	}

	public static function getPaymentNamespace()
	{
		return self::PAYMENT_NAMESPACE . '/' . self::EXTENSION_NAMESPACE . '/';
	}

	public static function getOnlinePaymentNamespace()
	{
		return self::PAYMENT_NAMESPACE . '/' . self::ONLINE_PAYMENT_NAMESPACE . '/';
	}

	public static function getMoneyOrderPaymentNamespace()
	{
		return self::PAYMENT_NAMESPACE . '/' . self::MONEY_ORDER_PAYMENT_NAMESPACE . '/';
	}

	public function isActive()
	{
		return Mage::getStoreConfigFlag(self::getNamespace() . 'active');
	}

	public function getUsername()
	{
		return Mage::getStoreConfig(self::getNamespace() . 'username');
	}

	public function getPassword()
	{
		return Mage::getStoreConfig(self::getNamespace() . 'password');
	}

	public function loadJquery()
	{
		if (!$this->isActive()) {
			return false;
		}
		return Mage::getStoreConfigFlag(self::getNamespace() . 'jquery');
	}

	public function getAvailableTypes($method, $array = true)
	{
		if (!$this->isActive()) {
			return false;
		}
		$types = Mage::getStoreConfig(self::getNamespace() . $method);
		return $array ? explode(',', $types) : $types;
	}

	public function getAvailableShippingTypes($array = true)
	{
		return $this->getAvailableTypes('shipping_types', $array);
//		$types = Mage::getStoreConfig(self::getNamespace() . 'shipping_types');
//		return $array ? explode(',', $types) : $types;
	}

	public function getAvailablePaymentTypes($array = true)
	{
		return $this->getAvailableTypes('payment_types', $array);
//		$types = Mage::getStoreConfig(self::getNamespace() . 'payment_types');
//		return $array ? explode(',', $types) : $types;
	}

	public function getPeymentTypes()
	{
		$types = array();
		$availableTypes = $this->getAvailablePaymentTypes();
		$condition = in_array('pardakhtonline', $availableTypes)
			|| in_array('pardakhtmoneyorder', $availableTypes);
		if ($condition) {
			$types['0'] = Mage::helper('pardakht')->__('Cash Payment');
		}
		if (in_array(self::EXTENSION_NAMESPACE, $availableTypes)) {
			$types['1'] = Mage::helper('pardakht')->__('COD Payment');
		}
		return $types;
	}

	public function isActiveShipping()
	{
		if (!$this->isActive()) {
			return false;
		}
		return Mage::getStoreConfigFlag(self::getShippingNamespace() . 'active');
	}

	public function getShippingTitle()
	{
		return Mage::getStoreConfig(self::getShippingNamespace() . 'title');
	}

	public function getOrigRegion()
	{
		return Mage::getStoreConfig(self::getShippingNamespace() . 'orig_region');
	}

	public function getOrigCity()
	{
		return Mage::getStoreConfig(self::getShippingNamespace() . 'orig_city');
	}

	public function isActivePayment()
	{
		return in_array(self::EXTENSION_NAMESPACE, $this->getAvailablePaymentTypes());
//		if (!$this->isActive()) {
//			return false;
//		}
//		return Mage::getStoreConfigFlag(self::getPaymentNamespace() . 'active');
	}

	public function getPaymentTitle()
	{
		return Mage::getStoreConfig(self::getPaymentNamespace() . 'title');
	}

	public function isActiveOnlinePayment()
	{
		return in_array(self::ONLINE_PAYMENT_NAMESPACE, $this->getAvailablePaymentTypes());
//		if (!$this->isActive()) {
//			return false;
//		}
//		return Mage::getStoreConfigFlag(self::getOnlinePaymentNamespace() . 'active');
	}

	public function getOnlinePaymentTitle()
	{
		return Mage::getStoreConfig(self::getOnlinePaymentNamespace() . 'title');
	}

	public function isActiveMoneyOrderPayment()
	{
		return in_array(self::MONEY_ORDER_PAYMENT_NAMESPACE, $this->getAvailablePaymentTypes());
//		if (!$this->isActive()) {
//			return false;
//		}
//		return Mage::getStoreConfigFlag(self::getMoneyOrderPaymentNamespace() . 'active');
	}

	public function getMoneyOrderPaymentTitle()
	{
		return Mage::getStoreConfig(self::getMoneyOrderPaymentNamespace() . 'title');
	}

	public function getDebugFlag()
	{
		return true;
	}
}

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


class Codnitive_Pardakht_Model_Carrier_Post
	extends Mage_Shipping_Model_Carrier_Abstract
	implements Mage_Shipping_Model_Carrier_Interface
{

	protected $_code = 'pardakht';
//    protected $_method = 'post';
	protected $_isFixed = true;

//	public function __contruct()
//	{
//		$quote = $this->_getCheckout()->getQuote();
//		Mage::register('grand_total', $quote->getGrandTotal());
//	}
//
	protected function _getCheckout()
	{
		return Mage::getSingleton('checkout/session');
	}

	protected function _getConfig()
	{
		return Mage::getModel('pardakht/config');
	}

//	public function isActive()
//	{
//		if (!$this->_getConfig()->isActive()) {
//			return false;
//		}
//
//		return parent::isActive();
//	}

	/**
	 * Enter description here...
	 *
	 * @param Mage_Shipping_Model_Rate_Request $data
	 * @return Mage_Shipping_Model_Rate_Result
	 */
	public function collectRates(Mage_Shipping_Model_Rate_Request $request)
	{
		if (!$this->_getConfig()->isActive() || !$this->getConfigFlag('active')) {
			return false;
		}

		if (!$this->getConfigFlag('include_virtual_price') && $request->getAllItems()) {
			foreach ($request->getAllItems() as $item) {
				if ($item->getParentItem()) {
					continue;
				}
				if ($item->getHasChildren() && $item->isShipSeparately()) {
					foreach ($item->getChildren() as $child) {
						if ($child->getProduct()->isVirtual()) {
							$request->setPackageValue($request->getPackageValue() - $child->getBaseRowTotal());
						}
					}
				}
				elseif ($item->getProduct()->isVirtual()) {
					$request->setPackageValue($request->getPackageValue() - $item->getBaseRowTotal());
				}
			}
		}

//        $sendTypes = array('1' => 'ems', '2' => 'rms'/*, '3'*/);
//        $shippingTypes = Mage::helper('pardakht')->getShippingTypes();
		$shippingTypes = $this->_getConfig()->getAvailableShippingTypes();
		$quote = $this->_getCheckout()->getQuote();
//        $options['grand_total']    = $request->getPackageValue();
		$grandTotal    = $request->getPackageValue();
		$options['package_weight'] = $request->getPackageWeight();
//        $buyStyles = array('0' => 'Cash Payment', '1' => 'Cash on Delivery Payment');
		$buyStyles = $this->_getConfig()->getPeymentTypes();
//		$options['origin_region'] = "83";
//		$options['origin_city'] = "1-قصر شيرين";
		$options['destination_region'] = $quote->getShippingAddress()->getPardakhtRegionId();
		$options['destination_city']   = $quote->getShippingAddress()->getPardakhtCity();

		$api = Mage::getModel('pardakht/api');
		$handlingFee = $api->getHandlingFee();
		$options['grand_total'] = $grandTotal + $handlingFee;

		$result = Mage::getModel('shipping/rate_result');

		foreach ($shippingTypes as $code/* => $methodName*/) {
			$methodName = Mage::helper('pardakht')->getShippingName($code);
			foreach ($buyStyles as $buyStyle => $styleTitle) {
				$options['buy_style'] = $buyStyle;
			$price = $api->getShippingPrice($options, $code);
			$price = urldecode($price);

			if (empty($price) || $price === 'Access Denied') {
				return false;
			}

//		if (!empty($rate)) {
			$method = Mage::getModel('shipping/rate_result_method');

			$method->setCarrier('pardakht');
			$method->setCarrierTitle($this->getConfigData('title'));

			$method->setMethod($methodName . '_' . $buyStyle);
//			$method->setMethodTitle((string) $grandTotal);
			$method->setMethodTitle(Mage::helper('pardakht')->__(strtoupper($methodName)) . ' - ' . $styleTitle);

			$method->setPrice($price);
			$method->setCost(0.0);

			$result->append($method);
//		}
			}
		}


			/*$method = Mage::getModel('shipping/rate_result_method');

			$method->setCarrier('pardakht');
			$method->setCarrierTitle($this->getConfigData('title'));

			$method->setMethod('post');
			$method->setMethodTitle(Mage::helper('shipping')->__('Store Pickup'));

			$method->setPrice(8000);

			$result->append($method);*/

		return $result;
	}

	/**
	 * Get allowed shipping methods
	 *
	 * @return array
	 */
	public function getAllowedMethods()
	{
		return array('pardakht' => Mage::helper('pardakht')->__('Pardakht'));
	}

}

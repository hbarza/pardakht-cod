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
 * One page checkout status
 *
 * @category   Codnitive
 * @package    Codnitive_Pardakht
 * @author     Hassan Barza <support@codnitive.com>
 */
class Codnitive_Pardakht_Block_Checkout_Onepage_Shipping extends Mage_Checkout_Block_Onepage_Shipping
{
//	protected $_pardakhtRegions = array(
////		'0' => 'Please select region, state or province',
//		'41' => 'East Azarbaijan',          '44' => 'West Azarbaijan',
//		'45' => 'Ardebil',                  '31' => 'Isfahan',
//		'84' => 'Ilam',                     '77' => 'Booshehr',
//		'26' => 'Alborz',   		        '21' => 'Tehran',
//		'38' => 'Charmahal-o Bakhtiyari',   '56' => 'South Khorasan',
//		'51' => 'Razavi Khorasan',          '58' => 'North Khorasan',
//		'61' => 'Khoozestan',		        '24' => 'Zanjan',
//		'23' => 'Semnan',                   '54' => 'Sistan-o Baloochestan',
//		'71' => 'Fars',                     '28' => 'Ghazvin',
//		'25' => 'Ghom',                     '87' => 'Kordestan',
//		'34' => 'Kerman',                   '83' => 'Kermanshah',
//		'74' => 'Kohkiloye-o Boyerahmad',   '17' => 'Golestan',
//		'13' => 'Gilan',                    '66' => 'Lorestan',
//		'15' => 'Mazandran',		        '86' => 'Markazi',
//		'76' => 'Hormozgan',                '81' => 'Hamedan',
//		'35' => 'Yazd'
//	);

	public function getCountryId()
	{
		$countryId = $this->getAddress()->getCountryId();
		if (is_null($countryId)) {
			$countryId = $this->helper('core')->getDefaultCountry();
		}
		return $countryId;
	}

	public function isIrCountryId()
	{
		return $this->helper('pardakht')->isIrCountryId($this->getCountryId());
//        return $this->getCountryId() === 'IR';
	}

	public function getCountryHtmlSelect($type)
	{
//		$countryId = $this->getAddress()->getCountryId();
//		if (is_null($countryId)) {
//			$countryId = Mage::helper('core')->getDefaultCountry();
//		}
		$countryId = $this->getCountryId();
		$select = $this->getLayout()->createBlock('core/html_select')
			->setName($type.'[country_id]')
			->setId($type.':country_id')
			->setTitle(Mage::helper('checkout')->__('Country'))
			->setClass('validate-select')
			->setValue($countryId)
			->setOptions($this->getCountryOptions());
		if ($type === 'shipping') {
			$select->setExtraParams('onchange="if(window.shipping)shipping.setSameAsBilling(false);Codnitive.pardakht.flipFields(this, \'shipping\');"');
		}

		return $select->getHtml();
	}

	public function getPardakhtRegions()
	{
		return $this->helper('pardakht')->getPardakhtRegions();
//        return $this->_pardakhtRegions;
	}
}

<?php
/**
 * LandOfCoder
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * http://www.landofcoder.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   LandOfCoder
 * @package    Lof_EstimateShippingRate
 * @copyright  Copyright (c) 2020 Landofcoder (http://www.LandOfCoder.com/)
 * @license    http://www.LandOfCoder.com/LICENSE-1.0.html
 */
namespace Lof\EstimateShippingRate\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Lof EstimateShippingRate Data Helper
 *
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_storeManager;
    /**
     * @param \Magento\Framework\App\Helper\Context
     * @param \Magento\Store\Model\StoreManagerInterface
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
        ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
    }

    public function getConfig($key, $store = null)
    {
        $store = $this->_storeManager->getStore($store);

        $result = $this->scopeConfig->getValue(
             $key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store);
        return $result;
    }
    /*
     * Return module status
     */

    public function getEnable($storeId = null)
    {
        return $this->getConfig('lof_estimateshippingrate/general/enable', $storeId);
    }

    public function getDefaultCountryCode($storeId = null){
        return $this->getConfig('general/country/default', $storeId);
    }
}

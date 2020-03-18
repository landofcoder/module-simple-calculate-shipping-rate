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
namespace Lof\EstimateShippingRate\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Block\Product\View;

class Estimate extends Template
{
    protected $_product_view;
    protected $_helperData;

    /**
     * Estimate constructor.
     * @param View $_product_view
     * @param Context $context
     * @param \Lof\EstimateShippingRate\Helper\Data $helperData
     * @param array $data
     */
    public function __construct(
        View $_product_view,
        Context $context,
        \Lof\EstimateShippingRate\Helper\Data $helperData,
        array $data = []
    ) {
        $this->_product_view = $_product_view;
        $this->_helperData = $helperData;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getProductInfo() {
        return $this->_product_view->getProduct();
    }
    /**
     * Prepare Content HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if(!$this->_helperData->getEnable()){
            return;
        }
        return parent::_toHtml();
    }

}
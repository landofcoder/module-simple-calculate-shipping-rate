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
namespace Lof\EstimateShippingRate\Controller\Product;
use \Lof\EstimateShippingRate\Helper\Data as LofData;
use Magento\{Framework\App\Action\Context,
    Framework\App\Action\Action,
    Catalog\Api\ProductRepositoryInterface,
    Quote\Model\QuoteFactory,
    Framework\Pricing\Helper\Data};

class Estimate extends Action
{
    protected $product_repository;
    protected $quote;
    protected $pricingHelper;
    protected $helperData;

    /**
     * Estimate constructor.
     * @param ProductRepositoryInterface $product_repository
     * @param QuoteFactory $quote
     * @param Data $pricingHelper
     * @param LofData $helperData
     * @param Context $context
     */
    public function __construct(
        ProductRepositoryInterface $product_repository,
        QuoteFactory $quote,
        Data $pricingHelper,
        LofData $helperData,
        Context $context
    ) {
        $this->product_repository = $product_repository;
        $this->quote = $quote;
        $this->pricingHelper = $pricingHelper;
        $this->helperData = $helperData;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $_params = $this->getRequest()->getParams();
        $storeId = isset($_params['storeId'])?$_params['storeId']:0;
        $response = [];
        if($this->helperData->getEnable($storeId)){
            if (
                empty($_params) ||
                !isset($_params['cep']) ||
                $_params['cep'] == ""
            ) {
                $response['error']['message'] = __('Postcode not informed');
            } else if (
                !isset($_params['product']) ||
                $_params['product'] == ""||
                $_params['product'] == 0 ||
                !is_numeric($_params['product'])
            ) {
                $response['error']['message'] = __('Amount reported is invalid');
            }

            if(!isset($response['error'])) {
                if (
                    !isset($_params['qty']) ||
                    $_params['qty'] == ""||
                    $_params['qty'] == 0 ||
                    !is_numeric($_params['qty'])
                ) {
                    $qty = 1;
                } else {
                    $qty = $_params['qty'];
                }

                try{
                    $_product = $this->product_repository->getById($_params['product']);
                    $default_country_id = $this->helperData->getDefaultCountryCode($storeId);
                    $quote = $this->quote->create();
                    $quote->addProduct($_product, $qty);
                    $quote->getShippingAddress()->setCountryId($default_country_id);
                    $quote->getShippingAddress()->setPostcode($_params['cep']);
                    $quote->getShippingAddress()->setCollectShippingRates(true);
                    $quote->getShippingAddress()->collectShippingRates();
                    $rates = $quote->getShippingAddress()->getShippingRatesCollection();

                    if(count($rates)>0){
                        $shipping_methods = [];

                        foreach ($rates as $rate) {
                            $_message = !$rate->getErrorMessage() ? "" : $rate->getErrorMessage();
                            $shipping_methods[$rate->getCarrierTitle()][] = array(
                                'title' => $rate->getMethodTitle(),
                                'price' => $this->pricingHelper->currency($rate->getPrice()),
                                'message' => $_message,
                            );
                        }

                        $response = $shipping_methods;
                    } else {
                        $response['error']['message'] = __('There is no shipping method available at this time.');
                    }

                } catch (\Exception $e){
                    $response['error']['message'] = $e->getMessage();
                    echo json_encode($response, true);
                    exit;
                }
            }
        }
        echo json_encode($response, true);
        exit;
    }
}
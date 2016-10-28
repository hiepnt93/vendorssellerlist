<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vnecoms\VendorsReview\Model\Review;

use Magento\Sales\Model\Detail as BaseOrder;
use Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * @method \Magento\Customer\Model\Customer getCustomer();
 * @method string getFirstname();
 * @method string getLastname();
 * @method string getMiddlename();
 * @method string getEmail();
 */
class Detail extends \Magento\Framework\Model\AbstractModel
{    
    const ENTITY = 'vendor_review_detail';
        
    /**
     * Model event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'vendor_review_detail';
    
    /**
     * Name of the event object
     *
     * @var string
     */
    protected $_eventObject = 'vendor_review_detail';


    /**
     * Initialize customer model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Vnecoms\VendorsReview\Model\ResourceModel\Review\Detail');
    }
}

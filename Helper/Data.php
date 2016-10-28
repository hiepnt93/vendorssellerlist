<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vnecoms\VendorsReview\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Data extends AbstractHelper
{
    const XML_PATH_HOME_REVIEW_NUM = 'vendors/vendorsreview/home_review_num';
    const XML_PATH_REVIEW_APPROVAL = 'vendors/vendorsreview/review_approval';
    const XML_PATH_NEW_REVIEW_EMAIL_TEMPLATE_CUSTOMER = 'vendors/vendorsreview/new_review_notification_customer';
    const XML_PATH_NEW_REVIEW_EMAIL_TEMPLATE_VENDOR = 'vendors/vendorsreview/new_review_notification_vendor';
    const XML_PATH_EMAIL_SENDER = 'vendors/vendorsreview/sender_email_identity';
    
    
    /**
     * @var \Vnecoms\Vendors\Helper\Email
     */
    protected $_emailHelper;
    
    
    /**
     * Constructor
     * 
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Vnecoms\Vendors\Helper\Email $emailHelper
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Vnecoms\Vendors\Helper\Email $emailHelper
    ) {
        parent::__construct($context);
        $this->_emailHelper = $emailHelper;
    }
    /**
     * Number of Reviews on Vendors's Home Page
     *
     * @return number
     */
    public function getNumOfReviewsOnVendorHomePage(){
        return $this->scopeConfig->getValue(self::XML_PATH_HOME_REVIEW_NUM);
    }
    
    /**
     * Is Required Approval
     * 
     * @return bool
     */
    public function isRequireApproval(){
        return $this->scopeConfig->getValue(self::XML_PATH_REVIEW_APPROVAL);
    }
    
    /**
     * Send new review notification email to customer
     * 
     * @param \Vnecoms\VendorsReview\Model\Review $review
     * @param \Vnecoms\Vendors\Model\Vendor $vendor
     * @param \Magento\Customer\Model\Customer $customer
     * @param \Magento\Catalog\Model\Product $product
     */
    public function sendNewReviewNotificationToCustomer(
        \Vnecoms\VendorsReview\Model\Review $review,
        \Vnecoms\Vendors\Model\Vendor $vendor,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Catalog\Model\Product $product
    ) {
        $customerEmail = $customer->getEmail();

        $this->_emailHelper->sendTransactionEmail(
            self::XML_PATH_NEW_REVIEW_EMAIL_TEMPLATE_CUSTOMER,
            \Magento\Framework\App\Area::AREA_FRONTEND,
            self::XML_PATH_EMAIL_SENDER,
            $customerEmail,
            ['vendor' => $vendor, 'customer' => $customer, 'review' => $review, 'reviewDetail'=>$review->getReviewDetail(), 'product' => $product]
        );
    }
    
    /**
     * Send new review notification email to vendor
     * 
     * @param \Vnecoms\VendorsReview\Model\Review $review
     * @param \Vnecoms\Vendors\Model\Vendor $vendor
     * @param \Magento\Customer\Model\Customer $customer
     * @param \Magento\Catalog\Model\Product $product
     */
     
    public function sendNewReviewNotificationToVendor(
        \Vnecoms\VendorsReview\Model\Review $review,
        \Vnecoms\Vendors\Model\Vendor $vendor,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Catalog\Model\Product $product
    ) {
        $vendorEmail = $vendor->getEmail();
    
        $this->_emailHelper->sendTransactionEmail(
            self::XML_PATH_NEW_REVIEW_EMAIL_TEMPLATE_VENDOR,
            \Magento\Framework\App\Area::AREA_FRONTEND,
            self::XML_PATH_EMAIL_SENDER,
            $vendorEmail,
            ['vendor' => $vendor, 'customer' => $customer, 'review' => $review, 'reviewDetail'=>$review->getReviewDetail(), 'product' => $product]
        );
    }
}

<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vnecoms\VendorsReview\Model\Review;

/**
 * @method \Magento\Customer\Model\Customer getCustomer();
 * @method string getFirstname();
 * @method string getLastname();
 * @method string getMiddlename();
 * @method string getEmail();
 */
class Link extends \Magento\Framework\Model\AbstractModel
{    
    const ENTITY = 'vendor_review_link';
        
    /**
     * Model event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'vendor_review_link';
    
    /**
     * Name of the event object
     *
     * @var string
     */
    protected $_eventObject = 'vendor_review_link';

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;
    
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;
    
    
    /**
     * Initialize customer model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Vnecoms\VendorsReview\Model\ResourceModel\Review\Link');
    }
    
    /**
     * Constructor
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Vnecoms\VendorsReview\Model\Review\DetailFactory $detailFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, null, null, $data);
    
        $this->_orderFactory = $orderFactory;
    }
    
    /**
     * Get Related Order
     * 
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder(){
        if(!$this->_order){
            $this->_order = $this->_orderFactory->create();
            $this->_order->load($this->getOrderId());
        }
        
        return $this->_order;
    }
    
    /**
     * Get Order Item
     * 
     * @return Ambigous <\Magento\Framework\DataObject, NULL, \Magento\Sales\Api\Data\OrderItemInterface>
     */
    public function getOrderItem(){
        return $this->getOrder()->getItemById($this->getOrderItemId());
    }
}

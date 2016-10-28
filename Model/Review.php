<?php
namespace Vnecoms\VendorsReview\Model;

class Review extends \Magento\Framework\Model\AbstractModel
{    
    const ENTITY = 'vendor_review';
    const TYPE_CUSTOMER = 1;
    const TYPE_VENDOR = 2;
    
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_NOT_APPROVED = 2;
    
    
    /**
     * Model event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'vendor_review';
    
    /**
     * Name of the event object
     *
     * @var string
     */
    protected $_eventObject = 'vendor_review';

    /**
     * @var \Vnecoms\VendorsReview\Model\Review\DetailFactory
     */
    protected $_reviewDetailFactory;
    
    /**
     * @var \Vnecoms\VendorsReview\Model\ResourceModel\Review\Detail\Collection
     */
    protected $_reviewDetailCollection;
    
    /**
     * @var \Vnecoms\VendorsReview\Model\Review\Detail
     */
    protected $_reviewDetail;
    
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;
    
    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product;
    
    /**
     * Constructor
     * 
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Vnecoms\VendorsReview\Model\Review\DetailFactory $detailFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Vnecoms\VendorsReview\Model\Review\DetailFactory $detailFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, null, null, $data);
        $this->_productFactory = $productFactory;
        $this->_reviewDetailFactory = $detailFactory;
    }
    
    /**
     * Initialize customer model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Vnecoms\VendorsReview\Model\ResourceModel\Review');
    }
    
    /**
     * Get Product
     * 
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct(){
        if(!$this->_product){
            $this->_product = $this->_productFactory->create();
            $this->_product->load($this->getProductId());
        }
        
        return $this->_product;
    }
    
    /**
     * Get Review Detail
     * 
     * @return \Vnecoms\VendorsReview\Model\Review\Detail
     */
    public function getReviewDetail(){
        if(!$this->_reviewDetail){
            $this->_reviewDetail = $this->getReviewDetailCollection()->getFirstItem();
        }
        
        return $this->_reviewDetail;
    }
    
    /**
     * Get Review Detail Collection
     * 
     * @return \Vnecoms\VendorsReview\Model\ResourceModel\Review\Detail\Collection
     */
    public function getReviewDetailCollection(){
        if(!$this->_reviewDetailCollection){
            $this->_reviewDetailCollection = $this->_reviewDetailFactory->create()->getCollection();
            $this->_reviewDetailCollection->addFieldToFilter('review_id',$this->getId())
                ->setOrder('created_at','DESC');
        }
        
        return $this->_reviewDetailCollection;
    }
    
    
}

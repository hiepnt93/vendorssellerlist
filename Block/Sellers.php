<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vnecoms\VendorsReview\Block;

use Magento\Catalog\Helper\ImageFactory as ImageHelperFactory;

/**
 * Review form block
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Form extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Magento\Framework\Url\EncoderInterface
     */
    protected $urlEncoder;

    /**
     * Message manager interface
     *
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * @var \Magento\Customer\Model\Url
     */
    protected $customerUrl;

    /**
     * @var array
     */
    protected $jsLayout;

    /**
     * @var \Vnecoms\VendorsPage\Helper\Data
     */
    protected $_pageHelper;
    
    /**
     * @var ImageHelperFactory
     */
    protected $imageHelperFactory;
    
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
    
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param \Magento\Review\Helper\Data $reviewData
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Review\Model\RatingFactory $ratingFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Customer\Model\Url $customerUrl,
        \Vnecoms\VendorsPage\Helper\Data $pageHelper,
        \Magento\Framework\Registry $coreRegistry,
        ImageHelperFactory $imageHelperFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->urlEncoder = $urlEncoder;
        $this->messageManager = $messageManager;
        $this->httpContext = $httpContext;
        $this->customerUrl = $customerUrl;
        $this->_pageHelper = $pageHelper;
        $this->imageHelperFactory = $imageHelperFactory;
        $this->_coreRegistry = $coreRegistry;
        
        $this->jsLayout = isset($data['jsLayout']) ? $data['jsLayout'] : [];
    }

    /**
     * Initialize review form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
    }

    public function getAllowWriteReviewFlag(){
        return true;
    }
    /**
     * @return string
     */
    public function getJsLayout()
    {
        return \Zend_Json::encode($this->jsLayout);
    }

    /**
     * Get Vendor
     * 
     * @return \Vnecoms\Vendors\Model\Vendor
     */
    public function getVendor(){
        return $this->_coreRegistry->registry('vendor');
    }
    
    /**
     * Get review product post action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->_pageHelper->getUrl(
            $this->getVendor(),
            'reviews/formPost',
            [
                '_secure' => $this->getRequest()->isSecure(),
                'item' => $this->getOrderItem()->getId(),
            ]
        );
    }

    /**
     * Get Order Item
     * 
     * @return \Magento\Sales\Model\Order\Item
     */
    public function getOrderItem(){
        return $this->_coreRegistry->registry('order_item');
    }
    
    /**
     * Get Product
     * 
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct(){
        return $this->getOrderItem()->getProduct();
    }
    
    /**
     * Get Product Image
     * 
     * @param \Magento\Catalog\Model\Product $product
     */
    public function getProductImageUrl(\Magento\Catalog\Model\Product $product){
        $helper = $this->imageHelperFactory->create()
        ->init($product, 'category_page_grid')->resize(100,80);
        
        return $helper->getUrl();
    }
    
    /**
     * Get review product id
     *
     * @return int
     */
    protected function getProductId()
    {
        return $this->getProduct()->getProductId();
    }
    
    /**
     * Get rating
     * 
     * @return multitype:\Magento\Framework\DataObject
     */
    public function getRatingOptions(){
        $options = [];
        for($i = 1; $i <=5; $i ++){
            $options[] = new \Magento\Framework\DataObject([
                'code' => $i,
                'value' => $i,
                'position' => $i   
            ]);
        }
        return $options;
    }
}

<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace Vnecoms\VendorsReview\Model\ResourceModel;

/**
 * Cms page mysql resource
 */
class Review extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Vnecoms\VendorsReview\Model\Review\DetailFactory
     */
    protected $_reviewDetailFactory;
    
    /**
     * Construct
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Vnecoms\VendorsReview\Model\Review\DetailFactory $reviewDetailFactory,
        $connectionName = null
    ) {
        $this->_reviewDetailFactory = $reviewDetailFactory;
        
        parent::__construct($context, $connectionName);
    }

    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object){
        if($object->getData('title')){
            $reviewDetail = $this->_reviewDetailFactory->create();
            $reviewDetail->setData([
                'review_id' => $object->getId(),
                'title' => $object->getData('title'),
                'detail' => $object->getData('detail'),
                'nickname' => $object->getData('nickname'),
                'created_at' => $object->getData('created_at'),
            ]);
            $reviewDetail->save();
        }
        return parent::_afterSave($object);
    }
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ves_vendor_review', 'review_id');
    }
    
    /**
     * Get average rating
     *
     * @param int $vendorId
     * @param int $type
     *
     * @return float:
     */
    
    public function getReviewCount(
        $entityPkValue,
        $type = \Vnecoms\VendorsReview\Model\Review::TYPE_VENDOR
    ) {
        $table = $this->getTable('ves_vendor_review');
        $connection = $this->getConnection();
        $select = $connection->select();
        $select->from(
            $table,
            [
                'rating' => 'count(review_id)',
            ]
        )->where(
            'type = :type'
        )->where(
            'entity_pk_value = :entity_pk_value'
        )->where(
            'status = :status'
        );
        $bind = [
            'entity_pk_value' => $entityPkValue,
            'type' => $type,
            'status' => \Vnecoms\VendorsReview\Model\Review::STATUS_APPROVED
        ];
        $reviewCount = $connection->fetchOne($select,$bind);
    
        return $reviewCount?$reviewCount:0;
    }
    
    
    /**
     * Get average rating
     * 
     * @param int $vendorId
     * @param int $type
     * 
     * @return float:
     */
    public function getAverageRating(
        $entityPkValue,
        $type = \Vnecoms\VendorsReview\Model\Review::TYPE_VENDOR
    ) {
        $table = $this->getTable('ves_vendor_review');
        $connection = $this->getConnection();
        $select = $connection->select();
        $select->from(
            $table,
            [
                'rating' => 'AVG(rating)',
            ]
        )->where(
            'type = :type'
        )->where(
            'entity_pk_value = :entity_pk_value'
        )->where(
            'status = :status'
        );
        $bind = [
            'entity_pk_value' => $entityPkValue,
            'type' => $type,
            'status' => \Vnecoms\VendorsReview\Model\Review::STATUS_APPROVED
        ];
        $rating = $connection->fetchOne($select,$bind);
        
        return $rating?$rating:0;
    }
}

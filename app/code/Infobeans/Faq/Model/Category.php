<?php
namespace Infobeans\Faq\Model;

use Infobeans\Faq\Api\Data\CategoryInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Category extends \Magento\Framework\Model\AbstractModel implements CategoryInterface, IdentityInterface
{

     
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
     
    const CACHE_TAG = 'infobeans_faq_category';

    
    protected $_cacheTag = 'infobeans_faq_category';

    
    protected $_eventPrefix = 'infobeans_faq_category';

    
    protected function _construct()
    {
        $this->_init('Infobeans\Faq\Model\ResourceModel\Category');
    }

     
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
     
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

     
    public function getId()
    {
        return $this->getData(self::CATEGORY_ID);
    }

    
     
    public function getCategoryName()
    {
        return $this->getData(self::CATEGORY_NAME);
    }

     
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

     
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

     
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

     
    public function isActive()
    {
        return (bool) $this->getData(self::IS_ACTIVE);
    }

     
    public function setId($id)
    {
        return $this->setData(self::CATEGORY_ID, $id);
    }

     
    public function setCategoryName($categoryName)
    {
        return $this->setData(self::CATEGORY_NAME, $categoryName);
    }

     
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }

     
    public function setCreationTime($creation_time)
    {
        return $this->setData(self::CREATION_TIME, $creation_time);
    }

     
    public function setUpdateTime($update_time)
    {
        return $this->setData(self::UPDATE_TIME, $update_time);
    }
     
    public function setIsActive($is_active)
    {
        return $this->setData(self::IS_ACTIVE, $is_active);
    }
}

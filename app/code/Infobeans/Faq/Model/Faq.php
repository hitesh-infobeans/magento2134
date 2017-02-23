<?php
namespace Infobeans\Faq\Model;

use Infobeans\Faq\Api\Data\FaqInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Faq extends \Magento\Framework\Model\AbstractModel implements FaqInterface, IdentityInterface
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
     
    const CACHE_TAG = 'infobeans_faq';
    
    protected $_cacheTag = 'infobeans_faq';
    
    protected $_eventPrefix = 'infobeans_faq';
    
    protected function _construct()
    {
        $this->_init('Infobeans\Faq\Model\ResourceModel\Faq');
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
        return $this->getData(self::FAQ_ID);
    }
     
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }
     
    public function getContent()
    {
        return $this->getData(self::CONTENT);
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
        return $this->setData(self::FAQ_ID, $id);
    }
     
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }
     
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
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

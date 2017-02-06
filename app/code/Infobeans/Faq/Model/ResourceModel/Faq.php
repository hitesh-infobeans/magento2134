<?php
namespace Infobeans\Faq\Model\ResourceModel;

/**
 * Faq mysql resource
 */
 
class Faq extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * Construct
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param string|null $resourcePrefix
     */
    
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    
    protected function _construct()
    {
        $this->_init('infobeans_faq', 'faq_id');
    }

     /**
     * Process faq data before saving
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     */
    
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {

        if ($object->isObjectNew() && !$object->hasCreationTime()) {
            $object->setCreationTime($this->_date->gmtDate());
        }

        $object->setUpdateTime($this->_date->gmtDate());

        return parent::_beforeSave($object);
    }

     /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \Infobeans\Faq\Model\Faq $object
     * @return \Zend_Db_Select
     */
    
    
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $select->where(
                'is_active = ?',
                1
            )->limit(
                1
            );
        }

        return $select;
    }
}

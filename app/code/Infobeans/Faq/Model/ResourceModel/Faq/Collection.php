<?php
namespace Infobeans\Faq\Model\ResourceModel\Faq;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
     /**
     * @var string
     */
    
    protected $_idFieldName = 'faq_id';
    
    /**
     * Define resource model
     *
     * @return void
     */
    
    protected function _construct()
    {
        $this->_init('Infobeans\Faq\Model\Faq', 'Infobeans\Faq\Model\ResourceModel\Faq');
    }
}

<?php
namespace Infobeans\Faq\Model\ResourceModel\Category;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    
    protected $_idFieldName = 'category_id';
    
    /**
     * Define resource model
     *
     * @return void
     */
    
    protected function _construct()
    {
        $this->_init('Infobeans\Faq\Model\Category', 'Infobeans\Faq\Model\ResourceModel\Category');
    }
    
    public function addFaqCategoryJoin()
    {
        $faqTable = $this->getTable('infobeans_faq');

            $this->getSelect()->join(
                ['f' => $faqTable],
                'main_table.category_id = f.category_id and f.is_active=1',
                []
            )->group(
                'main_table.category_id'
            );
        return $this;
    }
}

<?php
namespace Infobeans\Faq\Block\Sidebar;

/**
 * FAQ sidebar categories block
 */
class Category extends \Magento\Framework\View\Element\Template
{
    protected $categoryCollectionFactory;
    
    protected $resource;
    
    protected $scopeConfig;
    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Infobeans\Faq\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Framework\App\ResourceConnection $resource,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->resource = $resource;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->scopeConfig = $context->getScopeConfig();
    }
    
    public function getCategories()
    {
        $k = 'categories';
        if (!$this->hasData($k)) {
            $faqTable = $this->resource->getTableName('infobeans_faq');
            
            $categoryCollection = $this->categoryCollectionFactory
                ->create()
                ->addFilter('main_table.is_active', 1)
                ->addFaqCategoryFilter()
                ->setOrder('sort_order', 'asc');
            
            $this->setData($k, $categoryCollection);
        }
        return $this->getData($k);
    }
    
    /**
     * Return boolean
     * @return \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public function isEnableAccordian()
    {
        return $this->scopeConfig->getValue(
            'faq_section/general/enable_accordian',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}

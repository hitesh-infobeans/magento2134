<?php
 

namespace Infobeans\Faq\Block\Sidebar; 

/**
 * Faq sidebar categories block
 */
class Category extends \Magento\Framework\View\Element\Template
{
    protected $_categoryCollectionFactory;
    
    protected $_resource;
    
     public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Infobeans\Faq\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Framework\App\ResourceConnection $resource,     
        array $data = []
    ) {
        
        parent::__construct($context, $data);
        $this->_resource = $resource;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        
    }
    
    
    public function getCategories()
    { 
        $k = 'categories';
        if (!$this->hasData($k)) {
            
            $faqTable = $this->_resource->getTableName('infobeans_faq');
           
            
            $categoryCollection = $this->_categoryCollectionFactory 
                ->create()    
                ->addFilter('main_table.is_active',1)    
                 
                ->join(
                    ['f'=>$faqTable],
                    "main_table.category_id = f.category_id and f.is_active=1",
                    ['faq_id'=>'f.faq_id']    
                )  
                
                ->setOrder('sort_order','asc');
            
            $categoryCollection->getSelect()->group('main_table.category_id'); 
             
            $this->setData($k, $categoryCollection);
        }

        return $this->getData($k);
    }
    
    
}

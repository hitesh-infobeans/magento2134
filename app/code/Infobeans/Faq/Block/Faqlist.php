<?php
namespace Infobeans\Faq\Block;

use Infobeans\Faq\Api\Data\FaqInterface;
use Infobeans\Faq\Model\ResourceModel\Faq\Collection as FaqCollection;

class Faqlist extends \Magento\Framework\View\Element\Template implements
    \Magento\Framework\DataObject\IdentityInterface
{
    /**
     * @var \Infobeans\Faq\Model\ResourceModel\Faq\CollectionFactory
     */
    protected $faqCollectionFactory;
    
    protected $categoryCollectionFactory;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;
    
    protected $resource;
    
    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Infobeans\Faq\Model\ResourceModel\Faq\CollectionFactory $faqCollectionFactory,
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
     * @param array $data
     */
    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Infobeans\Faq\Model\ResourceModel\Faq\CollectionFactory $faqCollectionFactory,
        \Infobeans\Faq\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Framework\App\ResourceConnection $resource,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->faqCollectionFactory = $faqCollectionFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->resource = $resource;
        $this->scopeConfig = $context->getScopeConfig();
    }
    
    /**
     * Return Record Per Page
     * @return \Magento\Framework\App\Config\ScopeConfigInterface
     */
    
    public function getRecordPerPage()
    {
        return $this->scopeConfig->getValue(
            'faq_section/general/record_per_page',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Return Page Title
     * @return \Magento\Framework\App\Config\ScopeConfigInterface
     */
    
    public function getPageTitle()
    {
        return $this->scopeConfig->getValue(
            'faq_section/general/page_title',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Return Meta Title
     * @return \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public function getMetaTitle()
    {
        return $this->scopeConfig->getValue(
            'faq_section/general/meta_title',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Return Meta Keyword
     * @return \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public function getMetaKeyword()
    {
        return $this->scopeConfig->getValue(
            'faq_section/general/meta_keyword',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Return Meta Description
     * @return \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public function getMetaDescription()
    {
        return $this->scopeConfig->getValue(
            'faq_section/general/meta_description',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Set Page Title , Meta Title , Meta Keyword,
     * Meta Description
     */
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pageTitle = $this->getPageTitle();
        $metaTitle = $this->getMetaTitle();
        $metaKeyword = $this->getMetaKeyword();
        $metaDescription = $this->getMetaDescription();
        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        
        if ($pageTitle && $pageMainTitle) {
                $pageMainTitle->setPageTitle($pageTitle);
        }
       
        if ($metaTitle!="") {
            $this->pageConfig->getTitle()->set($metaTitle);
        }
        
        if ($metaKeyword!="") {
            $this->pageConfig->setKeywords($metaKeyword);
        }
        
        if ($metaDescription!="") {
            $this->pageConfig->setDescription($metaDescription);
        }
    }
    
    /**
     * @return \Infobeans\Faq\Model\ResourceModel\Faq\Collection
     */
    public function getFaqs()
    {
        $catId = $this->getRequest()->getPost('category_id');
        
        $page=($this->getRequest()->getPost('page'))? $this->getRequest()->getPost('page') : 1;
        //get values of current limit
        $pageSize=($this->getRecordPerPage())? $this->getRecordPerPage() : 10;
        
        if (!$catId) {
            $categoryCollection = $this->categoryCollectionFactory
                ->create()
                ->addFilter('main_table.is_active', 1)
                ->setOrder('sort_order', 'asc');
            $category=$categoryCollection->getFirstItem();
            $catId=$category->getId();
        }
        
        if (!$this->hasData('faqs')) {
            $faqs = $this->faqCollectionFactory
                ->create()
                ->addFilter('is_active', 1)
                ->addFilter('category_id', $catId)
                ->addOrder(
                    FaqInterface::CREATION_TIME,
                    FaqCollection::SORT_ORDER_ASC
                )
                ->setPageSize($pageSize)
                ->setCurPage($page)
                ->load();
            $this->setData('faqs', $faqs);
            
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'infobeans.faq.pager'
            )->setTemplate('Infobeans_Faq::pager.phtml')
                ->setAvailableLimit([$pageSize=>$pageSize])->setShowPerPage(true)->setCollection(
                    $faqs
                );
            $this->setChild('pager', $pager);
        }
        return $this->getData('faqs');
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\Infobeans\Faq\Model\Faq::CACHE_TAG . '_' . 'list'];
    }
    
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}

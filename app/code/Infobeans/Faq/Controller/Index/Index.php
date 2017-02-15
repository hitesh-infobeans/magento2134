<?php
namespace Infobeans\Faq\Controller\Index;

use Magento\Framework\App\Action\Context;

class Index extends \Magento\Framework\App\Action\Action
{
    /** @var  \Magento\Framework\View\Result\Page */
    protected $resultPageFactory;
    
    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
    
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * FAQ Index, shows a list of FAQs.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    
    public function execute()
    {
      
        return $this->resultPageFactory->create();
    }
}

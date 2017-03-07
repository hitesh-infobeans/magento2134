<?php
namespace Infobeans\Faq\Controller\Adminhtml\Faq;

use Magento\Backend\App\Action;
use Infobeans\Faq\Model\FaqFactory;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    
    private $faqFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        FaqFactory $faqFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->faqFactory = $faqFactory;
        parent::__construct($context);
    }

    /**
     * Is the user allowed to add/edit FAQ.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Infobeans_Faq::faq');
    }
    
    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
         
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Infobeans_Faq::faq')
            ->addBreadcrumb(__('FAQ'), __('FAQ'))
            ->addBreadcrumb(__('Manage FAQs'), __('Manage FAQs'));
        return $resultPage;
    }

    /**
     * Edit FAQ
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $faqId = $this->getRequest()->getParam('faq_id');
        if ($faqId) {
            $model = $this->faqFactory->create()->load($faqId);
           
            if (!$model->getId()) {
                $this->messageManager->addError(__('This FAQ no longer exists.'));
                 
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_getSession()->getFormData(true);
        
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('faq_faq', $model);
        
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $categoryId ? __('Edit FAQ') : __('New FAQ'),
            $categoryId ? __('Edit FAQ') : __('New FAQ')
        );
        
        $resultPage->getConfig()->getTitle()->prepend(__('FAQ'));
        
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getCategoryName() : __('New FAQ'));
         
        return $resultPage;
    }
}

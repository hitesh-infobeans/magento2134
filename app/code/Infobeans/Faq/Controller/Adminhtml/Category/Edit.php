<?php
namespace Infobeans\Faq\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Infobeans\Faq\Model\CategoryFactory;

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
    
    private $categoryFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        CategoryFactory $categoryFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context);
    }

    /**
     * Is the user allowed to add/edit category.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Infobeans_Faq::category');
    }
    
    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Infobeans_Faq::category')
            ->addBreadcrumb(__('Category'), __('Category'))
            ->addBreadcrumb(__('Manage Category'), __('Manage Category'));
        return $resultPage;
    }

    /**
     * Edit Faq
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $categoryId = $this->getRequest()->getParam('category_id');
        if ($categoryId) {
            $model = $this->categoryFactory->create()->load($categoryId);
           
            if (!$model->getId()) {
                $this->messageManager->addError(__('This Category no longer exists.'));
                 
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_getSession()->getFormData(true);
        
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('faq_category', $model);
        
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $categoryId ? __('Edit Category') : __('New Category'),
            $categoryId ? __('Edit Category') : __('New Category')
        );
        
        $resultPage->getConfig()->getTitle()->prepend(__('Category'));
        
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getCategoryName() : __('New Category'));
         
        return $resultPage;
    }
}

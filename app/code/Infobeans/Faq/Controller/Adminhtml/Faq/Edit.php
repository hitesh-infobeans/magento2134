<?php
namespace Infobeans\Faq\Controller\Adminhtml\Faq;

use Magento\Backend\App\Action;

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

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * Is the user allowed to add/edit faq.
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
            ->addBreadcrumb(__('Faq'), __('Faq'))
            ->addBreadcrumb(__('Manage Faqs'), __('Manage Faqs'));
        return $resultPage;
    }

    /**
     * Edit Faq
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('faq_id');
        $model = $this->_objectManager->create('Infobeans\Faq\Model\Faq');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This post no longer exists.'));
                 
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('faq_faq', $model);

        
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Faq') : __('New Faq'),
            $id ? __('Edit Faq') : __('New Faq')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Faqs'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Faq'));

        return $resultPage;
    }
}

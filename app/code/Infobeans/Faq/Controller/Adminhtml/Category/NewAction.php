<?php
namespace Infobeans\Faq\Controller\Adminhtml\Category;

class NewAction extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Backend\Model\View\Result\Forward
     */
    protected $resultForwardFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    /**
     * Is the user allowed to add new faq.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Infobeans_Faq::category');
    }

    /**
     * Forward to edit
     *
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        
        /** @var \Magento\Backend\Model\View\Result\Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}

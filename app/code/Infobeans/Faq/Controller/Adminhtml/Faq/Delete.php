<?php
namespace Infobeans\Faq\Controller\Adminhtml\Faq;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;
use Magento\Backend\App\Action\Context;
use Infobeans\Faq\Api\FaqRepositoryInterface;

class Delete extends \Magento\Backend\App\Action
{
    private $faqRepository;
    
    public function __construct(Context $context, FaqRepositoryInterface $faqRepository)
    {
        parent::__construct($context);
        $this->faqRepository=$faqRepository;
    }
    
    /**
     * Is the user allowed to delete FAQ.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Infobeans_Faq::faq');
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('faq_id');
       
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $this->faqRepository->deleteById($id);
                $this->messageManager->addSuccess(__('The FAQ has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['faq_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a FAQ to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}

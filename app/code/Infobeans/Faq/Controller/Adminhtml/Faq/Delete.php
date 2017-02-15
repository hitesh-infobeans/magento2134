<?php
namespace Infobeans\Faq\Controller\Adminhtml\Faq;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

class Delete extends \Magento\Backend\App\Action
{

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
                $model = $this->_objectManager->create('Infobeans\Faq\Model\Faq');
                $model->load($id);
                $model->delete();
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

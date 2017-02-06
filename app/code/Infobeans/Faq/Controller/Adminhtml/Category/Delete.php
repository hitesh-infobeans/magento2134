<?php
namespace Infobeans\Faq\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

class Delete extends \Magento\Backend\App\Action
{

    /**
     * Is the user allowed to delete category.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Infobeans_Faq::category');
    }

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('category_id');
       
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_objectManager->create('Infobeans\Faq\Model\Category');
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('The Category has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['category_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a Category to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}

<?php
namespace Infobeans\Faq\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;
use Infobeans\Faq\Api\CategoryRepositoryInterface;

class Delete extends \Magento\Backend\App\Action
{
    private $categoryRepository;
    
    public function __construct(
        Action\Context $context,
        CategoryRepositoryInterface $categoryRepository
    ) {
        parent::__construct($context);
        $this->categoryRepository=$categoryRepository;
    }
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
                $this->categoryRepository->deleteById($id);
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

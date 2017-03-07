<?php
namespace Infobeans\Faq\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;
use Infobeans\Faq\Model\CategoryFactory;

class Save extends \Magento\Backend\App\Action
{
    private $categoryFactory;
    
    public function __construct(Action\Context $context, CategoryFactory $categoryFactory)
    {
        parent::__construct($context);
        $this->categoryFactory=$categoryFactory;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Infobeans_Faq::category');
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $categoryId = $this->getRequest()->getParam('category_id');
            
            $model = $this->categoryFactory->create()->load($categoryId);
            
            if ($categoryId && $model->isObjectNew()) {
                $this->messageManager->addError(__('This Category no longer exists.'));
                $this->_redirect('adminhtml/*/');
                return;
            }
            
            $model->setData($data);
 
            $this->_eventManager->dispatch(
                'faq_category_prepare_save',
                ['faq_category' => $model, 'request' => $this->getRequest()]
            );

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved this Category.'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['category_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Somethingwent wrong while saving the Category.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath(
                '*/*/edit',
                ['category_id' => $this->getRequest()->getParam('category_id')]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }
}

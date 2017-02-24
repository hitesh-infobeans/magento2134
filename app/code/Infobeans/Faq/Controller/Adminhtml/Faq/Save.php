<?php
namespace Infobeans\Faq\Controller\Adminhtml\Faq;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\TestFramework\ErrorLog\Logger;
use Infobeans\Faq\Model\FaqFactory;

class Save extends \Magento\Backend\App\Action
{ 
    private $faqFactory;
    
    public function __construct
    (
        Context $context,
        FaqFactory $faqFactory
    )
    {
        parent::__construct($context);
       // $this->faqRepository=$faqRepository;
        $this->faqFactory=$faqFactory;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Infobeans_Faq::faq');
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
            $faqId = $this->getRequest()->getParam('faq_id');
            
            $model = $this->faqFactory->create()->load($faqId);
            
            if ($faqId && $model->isObjectNew()) {
                $this->messageManager->addError(__('This FAQ no longer exists.'));
                $this->_redirect('adminhtml/*/');
                return;
            }  
            
            $model->setData($data);            

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved this FAQ.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['faq_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Somethingwent wrong while saving the FAQ.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['faq_id' => $this->getRequest()->getParam('faq_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

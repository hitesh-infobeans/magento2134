<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Infobeans\Faq\Model;

use Infobeans\Faq\Api\FaqRepositoryInterface;
use Infobeans\Faq\Model\FaqFactory;
 
class FaqRepository implements FaqRepositoryInterface
{
    private $faqFactory;
    private $faqResource;
    
    public function __construct(
        \Infobeans\Faq\Model\ResourceModel\Faq $faqResource,
        FaqFactory $faqFactory
    )
    {
        $this->faqResource = $faqResource;
        $this->faqFactory = $faqFactory;       
    } 
    
    public function get($faqId)
    {
        $faq = $this->faqFactory->create();
        $this->faqResource->load($faq, $faqId);
        if(!$faq->getId()) {
            throw new NoSuchEntityException('Faq does not exist');
        }
        return $faq;        
    }
    
    
    public function save(\Infobeans\Faq\Api\Data\FaqInterface $faq)
    {
        $this->faqResource->save($faq);
        return $faq->getId();
    }
    
    public function deleteById($faqId)
    {
        $faq = $this->faqFactory->create();
        $faq->setId($faqId);
        
        if ( $this->faqResource->delete($faq)) {
            return true;
        } else {
            return false;
        }
    }
}

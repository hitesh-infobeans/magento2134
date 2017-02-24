<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Infobeans\Faq\Api;

interface FaqRepositoryInterface
{    
    public function get($faqId);
    
    public function save(\Infobeans\Faq\Api\Data\FaqInterface $faq);
        
    public function deleteById($faqId);
}

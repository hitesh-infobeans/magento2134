<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Infobeans\Faq\Model;

use Infobeans\Faq\Api\CategoryRepositoryInterface;
use Infobeans\Faq\Model\CategoryFactory;
 
class CategoryRepository implements CategoryRepositoryInterface
{
    private $categoryFactory;
    private $categoryResource;
    
    public function __construct(
        \Infobeans\Faq\Model\ResourceModel\Category $categoryResource,
        CategoryFactory $categoryFactory
    )
    {
        $this->categoryResource = $categoryResource;
        $this->categoryFactory = $categoryFactory;
    }
    
    public function get($categoryId)
    {
        $category = $this->categoryFactory->create();
        $this->categoryResource->load($category, $categoryId);
        if(!$category->getId()) {
            throw new NoSuchEntityException('Category does not exist');
        }
        return $category;        
    }    
    
    public function save(\Infobeans\Faq\Api\Data\CategoryInterface $category)
    {
        $this->categoryResource->save($category);
        return $category->getId();
    }
    
    public function deleteById($categoryId)
    {
        $category = $this->categoryFactory->create();
        $category->setId($categoryId);
        
        if ($this->categoryResource->delete($category)) {
            return true;
        } else {
            return false;
        }
    }
}

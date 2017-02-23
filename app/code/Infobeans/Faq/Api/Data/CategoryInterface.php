<?php
namespace Infobeans\Faq\Api\Data;

interface CategoryInterface
{
    const CATEGORY_ID       = 'category_id';
    const CATEGORY_NAME         = 'category_name';
    const SORT_ORDER       = 'sort_order';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME   = 'update_time';
    const IS_ACTIVE     = 'is_active';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();
     
    /**
     * Get Category Name
     *
     * @return string|null
     */
    public function getCategoryName();

    /**
     * Get Sort Order
     *
     * @return string|null
     */
    public function getSortOrder();

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Is active
     *
     * @return bool|null
     */
    public function isActive();

    /**
     * Set ID
     *
     * @param int $id
     */
    public function setId($id);
     
    /**
     * Set Category Name
     *
     * @param string $title
     */
    public function setCategoryName($categoryName);

    /**
     * Set Sort Order
     *
     * @param string $content
     */
    public function setSortOrder($sortOrder);

    /**
     * Set creation time
     *
     * @param string $creationTime
     */
    public function setCreationTime($creationTime);

    /**
     * Set update time
     *
     * @param string $updateTime
     */
    public function setUpdateTime($updateTime);

    /**
     * Set is active
     *
     * @param int|bool $isActive
     */
    public function setIsActive($isActive);
}

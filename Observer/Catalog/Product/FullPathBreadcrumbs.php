<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magefan\Catalog\Observer\Catalog\Product;

class FullPathBreadcrumbs implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager; 

    /**
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Store\Model\StoreManagerInterface $StoreManagerInterface
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager = null
    ) {
        $this->registry=$registry;
        $this->categoryRepository = $categoryRepository;
        $this->storeManager = $storeManager ?: \Magento\Framework\App\ObjectManager::getInstance()
            ->get(\Magento\Store\Model\StoreManagerInterface::class);
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $product = $observer->getEvent()->getProduct();
        if ($product && !$this->registry->registry('current_category')) {
            $categories = $product->getAvailableInCategories();
            $productCategory = null;
            $level = -1;
            $rootCategoryId = $this->storeManager->getStore()->getRootCategoryId();

            if ($categories) {
                foreach ($categories as $categoryId) {
                    try {
                        $category = $this->categoryRepository->get($categoryId);
                        if ($category->getIsActive()
                            && $category->getLevel() > $level
                            && in_array($rootCategoryId, $category->getPathIds())
                        ) {
                            $level = $category->getLevel();
                            $productCategory = $category;
                        }
                    } catch (\Exception $e) {}
                }
            }

            if ($productCategory) {
                $this->registry->register('current_category', $productCategory, true);
            }
        }
    }
}

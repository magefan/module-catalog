<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magefan\Catalog\Observer\Catalog\Product;

class FullPathBreadcrumbs implements \Magento\Framework\Event\ObserverInterface
{
    protected $registry;

    protected $categoryRepository;

    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
    ) {
        $this->registry=$registry;
        $this->categoryRepository = $categoryRepository;
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
            if ($categories) {
                foreach ($categories as $categoryId) {
                    try {
                        $category = $categ = $this->categoryRepository->get($categoryId);
                        if ($category->getLevel() > $level) {
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

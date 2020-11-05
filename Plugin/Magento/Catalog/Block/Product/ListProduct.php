<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magefan\Catalog\Plugin\Magento\Catalog\Block\Product;

/**
 * Class ListProduct
 * @package Magefan\Catalog\Plugin\Magento\Catalog\Block\Product
 */
class ListProduct
{
    /**
     *  Max. dentities count
     */
    const MAX_COUNT = 100;

    /**
     * Slice identities.
     * @param \Magento\Catalog\Block\Product\ListProduct $subject
     * @param array $identities
     */
    public function afterGetIdentities(
        \Magento\Catalog\Block\Product\ListProduct $subject,
        $identities
    ) {
        if (count($identities) > self::MAX_COUNT) {
            $identities = array_slice($identities, 0, self::MAX_COUNT);
            $category = $subject->getLayer()->getCurrentCategory();
            if ($category) {
                $identities[] =  \Magento\Catalog\Model\Product::CACHE_PRODUCT_CATEGORY_TAG . '_' . $category->getId();
            }
        }

        return $identities;
    }
}

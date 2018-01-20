<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */

namespace Magefan\Catalog\Plugin\Magento\Catalog\Model\Template;

use Magento\Cms\Model\Template\FilterProvider;

class Filter
{

    /**
     * @var FilterProvider
     */
    protected $filterProvider;

    /**
     * Filter constructor.
     * @param FilterProvider $filterProvider
     */
    public function __construct(
        FilterProvider $filterProvider
    ) {
        $this->filterProvider = $filterProvider;
    }

    /**
     * @param \Magento\Catalog\Model\Template\Filter $subject
     * @param string $returnValue
     * @return string
     */
    public function afterFilter(\Magento\Catalog\Model\Template\Filter $subject, $returnValue)
    {
        return $this->filterProvider
            ->getBlockFilter()
            ->filter($returnValue);
    }
}

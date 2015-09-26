<?php
namespace Firebear\ConfigurableProducts\Plugin\Helper\Catalog;

class Product
{
    protected $catalogProductTypeConfigurable;

    protected $catalogSession;

    /**
     * @param \Magento\ConfigurableProduct\Model\Resource\Product\Type\Configurable $catalogProductTypeConfigurable
     * @param \Magento\Catalog\Model\Session $catalogSession
     */
    public function __construct(
        \Magento\ConfigurableProduct\Model\Resource\Product\Type\Configurable $catalogProductTypeConfigurable,
        \Magento\Catalog\Model\Session $catalogSession
    ) {
        $this->catalogProductTypeConfigurable = $catalogProductTypeConfigurable;
        $this->catalogSession = $catalogSession;
    }

    /**
     * @param \Magento\Catalog\Helper\Product $subject
     * @param $productId
     * @param $controller
     * @param null $params
     * @return array
     */
    public function beforeInitProduct(\Magento\Catalog\Helper\Product $subject, $productId, $controller, $params = null)
    {
        if($productId) {
            $parentIds = $this->catalogProductTypeConfigurable->getParentIdsByChild($productId);

            if (count($parentIds) > 0) {
                $this->catalogSession->setData(
                    'firebear_configurableproducts',
                    array(
                        'child_id' => $productId,
                        'parent_id' => $parentIds[0]
                    )
                );
                $productId = $parentIds[0];
            }
        }

        return [$productId, $controller, $params];
    }
}
<?php
/**
 * Copyright since 2007 Carmine Di Gruttola
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    cdigruttola <c.digruttola@hotmail.it>
 * @copyright Copyright since 2007 Carmine Di Gruttola
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Oksydan\IsProductExtraTabs\Repository\ProductExtraTabProductRepository")
 *
 * @ORM\Table()
 */
class ProductExtraTabProduct
{
    /**
     * @var int
     *
     * @ORM\Id
     *
     * @ORM\Column(name="id_product_extra_tab", type="integer")
     *
     * @Orm\GeneratedValue(strategy="NONE")
     */
    private $id_product_extra_tab;
    /**
     * @var int
     *
     * @ORM\Id
     *
     * @ORM\Column(name="id_product", type="integer")
     *
     * @Orm\GeneratedValue(strategy="NONE")
     */
    private $id_product;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity="Oksydan\IsProductExtraTabs\Entity\ProductExtraTab", cascade={"persist", "remove"}, inversedBy="productExtraTabProducts")
     *
     * @ORM\JoinColumn(name="id_product_extra_tab", referencedColumnName="id_product_extra_tab", nullable=false, onDelete="CASCADE")
     */
    private $productExtraTab;
    /**
     * @ORM\OneToMany(targetEntity="Oksydan\IsProductExtraTabs\Entity\ProductExtraTabProductLang", cascade={"persist", "remove"}, mappedBy="productExtraTabProduct")
     */
    private $productExtraTabProductLangs;

    public function __construct()
    {
        $this->productExtraTabProductLangs = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getIdProductExtraTab(): int
    {
        return $this->id_product_extra_tab;
    }

    /**
     * @param int $id_product_extra_tab
     *
     * @return ProductExtraTabProduct
     */
    public function setIdProductExtraTab(int $id_product_extra_tab): ProductExtraTabProduct
    {
        $this->id_product_extra_tab = $id_product_extra_tab;

        return $this;
    }

    /**
     * @return int
     */
    public function getIdProduct(): int
    {
        return $this->id_product;
    }

    /**
     * @param int $id_product
     *
     * @return ProductExtraTabProduct
     */
    public function setIdProduct(int $id_product): ProductExtraTabProduct
    {
        $this->id_product = $id_product;

        return $this;
    }

    /**
     * @return bool
     */
    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return ProductExtraTabProduct $this
     */
    public function setActive(bool $active): ProductExtraTabProduct
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return ProductExtraTab
     */
    public function getProductExtraTab(): ProductExtraTab
    {
        return $this->productExtraTab;
    }

    /**
     * @param ProductExtraTab $extraTab
     *
     * @return ProductExtraTabProduct
     */
    public function setProductExtraTab(ProductExtraTab $extraTab): ProductExtraTabProduct
    {
        $this->productExtraTab = $extraTab;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getProductExtraTabProductLangs()
    {
        return $this->productExtraTabProductLangs;
    }

    /**
     * @param int $langId
     *
     * @return ProductExtraTabProductLang|null
     */
    public function getProductExtraTabProductLangByLangId(int $langId): ?ProductExtraTabProductLang
    {
        foreach ($this->productExtraTabProductLangs as $sliderLang) {
            if ($langId === $sliderLang->getLang()->getId()) {
                return $sliderLang;
            }
        }

        return null;
    }

    /**
     * @param ProductExtraTabProductLang $sliderLang
     *
     * @return ProductExtraTabProduct $this
     */
    public function addProductExtraTabProductLang(ProductExtraTabProductLang $sliderLang): ProductExtraTabProduct
    {
        $sliderLang->setProductExtraTabProduct($this);
        $this->productExtraTabProductLangs->add($sliderLang);

        return $this;
    }
}

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
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PrestaShopBundle\Entity\Shop;

/**
 * @ORM\Entity(repositoryClass="Oksydan\IsProductExtraTabs\Repository\ProductExtraTabRepository")
 *
 * @ORM\Table()
 */
class ProductExtraTab
{
    /**
     * @var int
     *
     * @ORM\Id
     *
     * @ORM\Column(name="id_product_extra_tab", type="integer")
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Oksydan\IsProductExtraTabs\Entity\ProductExtraTabProduct", cascade={"persist", "remove"}, mappedBy="productExtraTab")
     */
    private $productExtraTabProducts;
    /**
     * @ORM\OneToMany(targetEntity="Oksydan\IsProductExtraTabs\Entity\ProductExtraTabDefaultLang", cascade={"persist", "remove"}, mappedBy="productExtraTab")
     */
    private $productExtraTabDefaultLangs;

    /**
     * @ORM\ManyToMany(targetEntity="PrestaShopBundle\Entity\Shop", cascade={"persist"})
     *
     * @ORM\JoinTable(
     *      joinColumns={@ORM\JoinColumn(name="id_product_extra_tab", referencedColumnName="id_product_extra_tab")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_shop", referencedColumnName="id_shop", onDelete="CASCADE")}
     * )
     */
    private $shops;

    public function __construct()
    {
        $this->shops = new ArrayCollection();
        $this->productExtraTabProducts = new ArrayCollection();
        $this->productExtraTabDefaultLangs = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return ProductExtraTab $this
     */
    public function setActive(bool $active): ProductExtraTab
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     *
     * @return ProductExtraTab $this
     */
    public function setPosition(int $position): ProductExtraTab
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return ProductExtraTab $this
     */
    public function setName(string $name): ProductExtraTab
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param Shop $shop
     *
     * @return ProductExtraTab $this
     */
    public function addProduct(Shop $shop): ProductExtraTab
    {
        $this->shops[] = $shop;

        return $this;
    }

    /**
     * @param Shop $shop
     *
     * @return ProductExtraTab $this
     */
    public function removeShop(Shop $shop): ProductExtraTab
    {
        $this->shops->removeElement($shop);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getShops(): Collection
    {
        return $this->shops;
    }

    /**
     * @return ProductExtraTab $this
     */
    public function clearShops(): ProductExtraTab
    {
        $this->shops->clear();

        return $this;
    }

    /**
     * @param Shop $shop
     *
     * @return ProductExtraTab $this
     */
    public function addShop(Shop $shop): ProductExtraTab
    {
        $this->shops[] = $shop;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getProductExtraTabProducts()
    {
        return $this->productExtraTabProducts;
    }

    /**
     * @param int $productId
     *
     * @return ProductExtraTabProduct|null
     */
    public function getProductExtraTabProductByProductId(int $productId): ?ProductExtraTabProduct
    {
        foreach ($this->productExtraTabProducts as $extraTabProduct) {
            if ($productId === $extraTabProduct->getIdProduct()) {
                return $extraTabProduct;
            }
        }

        return null;
    }

    /**
     * @param ProductExtraTabProduct $extraTabProduct
     *
     * @return ProductExtraTab $this
     */
    public function addProductExtraTabProduct(ProductExtraTabProduct $extraTabProduct): ProductExtraTab
    {
        $extraTabProduct->setProductExtraTab($this);
        $this->productExtraTabProducts->add($extraTabProduct);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getProductExtraTabDefaultLangs()
    {
        return $this->productExtraTabDefaultLangs;
    }

    /**
     * @param int $langId
     *
     * @return ProductExtraTabDefaultLang|null
     */
    public function getProductExtraTabDefaultLangByLangId(int $langId): ?ProductExtraTabDefaultLang
    {
        foreach ($this->productExtraTabDefaultLangs as $sliderLang) {
            if ($langId === $sliderLang->getLang()->getId()) {
                return $sliderLang;
            }
        }

        return null;
    }

    /**
     * @param ProductExtraTabDefaultLang $sliderLang
     *
     * @return ProductExtraTab $this
     */
    public function addProductExtraTabDefaultLang(ProductExtraTabDefaultLang $sliderLang): ProductExtraTab
    {
        $sliderLang->setProductExtraTab($this);
        $this->productExtraTabDefaultLangs->add($sliderLang);

        return $this;
    }
}

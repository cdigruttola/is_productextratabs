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

use Doctrine\ORM\Mapping as ORM;
use PrestaShopBundle\Entity\Lang;

/**
 * @ORM\Table()
 *
 * @ORM\Entity
 */
class ProductExtraTabProductLang
{
    /**
     * @var int
     *
     * @ORM\Id
     *
     * @ORM\Column(name="id_product_extra_tab", type="integer")
     * @Orm\GeneratedValue(strategy="NONE")
     */
    private $id_product_extra_tab;
    /**
     * @var int
     *
     * @ORM\Id
     *
     * @ORM\Column(name="id_product", type="integer")
     * @Orm\GeneratedValue(strategy="NONE")
     */
    private $id_product;

    /**
     * @var ProductExtraTabProduct
     *
     * @ORM\ManyToOne(targetEntity="Oksydan\IsProductExtraTabs\Entity\ProductExtraTabProduct", inversedBy="productExtraTabProductLangs")
     *
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_product_extra_tab", referencedColumnName="id_product_extra_tab", nullable=false),
     *   @ORM\JoinColumn(name="id_product", referencedColumnName="id_product", nullable=false)
     * })
     */
    private $productExtraTabProduct;

    /**
     * @var Lang
     *
     * @ORM\Id
     *
     * @ORM\ManyToOne(targetEntity="PrestaShopBundle\Entity\Lang")
     *
     * @ORM\JoinColumn(name="id_lang", referencedColumnName="id_lang", nullable=false, onDelete="CASCADE")
     */
    private $lang;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="text")
     */
    private $title;
    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @return ProductExtraTabProduct
     */
    public function getProductExtraTabProduct(): ProductExtraTabProduct
    {
        return $this->productExtraTabProduct;
    }

    /**
     * @param ProductExtraTabProduct $extraTab
     *
     * @return ProductExtraTabProductLang
     */
    public function setProductExtraTabProduct(ProductExtraTabProduct $extraTab): ProductExtraTabProductLang
    {
        $this->productExtraTabProduct = $extraTab;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return ProductExtraTabProductLang $this
     */
    public function setTitle(string $title): ProductExtraTabProductLang
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return ProductExtraTabProductLang $this
     */
    public function setContent(string $content): ProductExtraTabProductLang
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Lang
     */
    public function getLang(): Lang
    {
        return $this->lang;
    }

    /**
     * @param Lang $lang
     *
     * @return ProductExtraTabProductLang $this
     */
    public function setLang(Lang $lang): ProductExtraTabProductLang
    {
        $this->lang = $lang;

        return $this;
    }
}

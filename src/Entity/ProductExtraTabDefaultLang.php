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
class ProductExtraTabDefaultLang
{
    /**
     * @var ProductExtraTab
     *
     * @ORM\Id
     *
     * @ORM\ManyToOne(targetEntity="Oksydan\IsProductExtraTabs\Entity\ProductExtraTab", inversedBy="productExtraTabDefaultLang")
     *
     * @ORM\JoinColumn(name="id_product_extra_tab", referencedColumnName="id_product_extra_tab", nullable=false)
     */
    private $productExtraTab;

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
     * @return ProductExtraTab
     */
    public function getProductExtraTab(): ProductExtraTab
    {
        return $this->productExtraTab;
    }

    /**
     * @param ProductExtraTab $extraTab
     *
     * @return ProductExtraTabDefaultLang $this
     */
    public function setProductExtraTab(ProductExtraTab $extraTab): ProductExtraTabDefaultLang
    {
        $this->productExtraTab = $extraTab;

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
     * @return ProductExtraTabDefaultLang $this
     */
    public function setTitle(string $title): ProductExtraTabDefaultLang
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
     * @return ProductExtraTabDefaultLang $this
     */
    public function setContent(string $content): ProductExtraTabDefaultLang
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
     * @return ProductExtraTabDefaultLang $this
     */
    public function setLang(Lang $lang): ProductExtraTabDefaultLang
    {
        $this->lang = $lang;

        return $this;
    }
}

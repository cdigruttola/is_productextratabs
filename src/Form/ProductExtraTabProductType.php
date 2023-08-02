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

namespace Oksydan\IsProductExtraTabs\Form;

use Oksydan\IsProductExtraTabs\Translations\TranslationDomains;
use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\CleanHtml;
use PrestaShopBundle\Form\Admin\Type\FormattedTextareaType;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductExtraTabProductType extends TranslatorAwareType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var int $idExtraTab */
        $idExtraTab = $options['data']['id_product_extra_tab'] ?? 0;
        $builder
            ->add('id_product', HiddenType::class)
            ->add('id_product_extra_tab', HiddenType::class)
            ->add("name_$idExtraTab", TextType::class, [
                'label' => $this->trans('Name', TranslationDomains::ADMIN_GLOBAL),
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => [
                    'readonly' => true,
                ],
            ])
            ->add("title_$idExtraTab", TranslatableType::class, [
                'type' => TextType::class,
                'locales' => $this->locales,
                'label' => $this->trans('Title', TranslationDomains::ADMIN_GLOBAL),
                'required' => true,
                'options' => ['constraints' => [
                    new NotBlank(),
                ]],
            ])
            ->add("content_$idExtraTab", TranslatableType::class, [
                'type' => FormattedTextareaType::class,
                'label' => $this->trans('Content', TranslationDomains::TRANSLATION_DOMAIN_ADMIN),
                'locales' => $this->locales,
                'required' => true,
                'options' => [
                    'constraints' => [
                        new CleanHtml([
                            'message' => $this->trans(
                                '%s is invalid.',
                                'Admin.Notifications.Error'
                            ),
                        ]),
                    ],
                ],
            ])
            ->add("active_$idExtraTab", SwitchType::class, [
                'label' => $this->trans('Active on this product', TranslationDomains::TRANSLATION_DOMAIN_ADMIN),
                'required' => true,
            ]);
    }
}

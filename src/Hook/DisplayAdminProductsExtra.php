<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Hook;

use Oksydan\IsProductExtraTabs\Form\ProductExtraTabProductType;
use Oksydan\IsProductExtraTabs\Translations\TranslationDomains;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;
use Symfony\Component\Form\FormFactory;
use Twig\Environment;

class DisplayAdminProductsExtra extends AbstractDisplayHook
{
    private const TEMPLATE_FILE = 'extra-tabs.html.twig';

    protected function getTemplate(): string
    {
        return self::TEMPLATE_FILE;
    }

    public function execute(array $params)
    {
        if (!$this->shouldBlockBeDisplayed($params)) {
            return false;
        }

        $productId = $params['id_product'] ?? \Tools::getValue('id_product');

        /** @var FormDataProviderInterface $formDataProvider */
        $formDataProvider = $this->module->get('oksydan.is_product_extra_tab.form.identifiable_object.data_provider.product_extra_tab_product_form_data_provider');
        /** @var $twig Environment */
        $twig = $this->module->get('twig');
        /** @var FormFactory $formFactory */
        $formFactory = $this->module->get('form.factory');

        $data = $formDataProvider->getData($productId);

        $forms = [];
        foreach ($data as $key => $tab) {
            $form = $formFactory->create(ProductExtraTabProductType::class, $tab);
            $forms[$key] = $form->createView();
        }

        return $twig->render($this->getTemplateFullPath(), [
            'forms' => $forms,
            'translationDomain' => TranslationDomains::TRANSLATION_DOMAIN_ADMIN,
            'languages' => implode(',', \Language::getIDs()),
            ]);
    }
}

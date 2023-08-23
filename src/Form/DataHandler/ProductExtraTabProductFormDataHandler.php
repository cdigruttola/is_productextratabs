<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Form\DataHandler;

use Doctrine\ORM\EntityManagerInterface;
use Oksydan\IsProductExtraTabs\Entity\ProductExtraTab;
use Oksydan\IsProductExtraTabs\Entity\ProductExtraTabProduct;
use Oksydan\IsProductExtraTabs\Entity\ProductExtraTabProductLang;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use PrestaShopBundle\Entity\Repository\LangRepository;

class ProductExtraTabProductFormDataHandler implements FormDataHandlerInterface
{
    /**
     * @var LangRepository
     */
    private $langRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var array
     */
    private $languages;

    public function __construct(
        LangRepository $langRepository,
        EntityManagerInterface $entityManager,
        array $languages
    ) {
        $this->langRepository = $langRepository;
        $this->entityManager = $entityManager;
        $this->languages = $languages;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        /** @var ProductExtraTab $extraTab */
        $extraTab = $this->entityManager->getRepository(ProductExtraTab::class)->find($data['id_product_extra_tab']);

        $extraTabProduct = new ProductExtraTabProduct();

        $extraTabProduct->setActive($data['active']);
        $extraTabProduct->setIdProductExtraTab($data['id_product_extra_tab']);
        $extraTabProduct->setProductExtraTab($extraTab);
        $extraTabProduct->setIdProduct($data['id_product']);

        foreach ($this->languages as $language) {
            $langId = (int) $language['id_lang'];
            $lang = $this->langRepository->findOneBy(['id' => $langId]);
            $productExtraTabProductLang = new ProductExtraTabProductLang();

            $productExtraTabProductLang
                ->setIdProductExtraTab($data['id_product_extra_tab'])
                ->setIdProduct($data['id_product'])
                ->setLang($lang)
                ->setTitle($data['title'][$langId] ?? '')
                ->setContent($data['content'][$langId] ?? '');

            $extraTabProduct->addProductExtraTabProductLang($productExtraTabProductLang);
        }

        $this->entityManager->persist($extraTabProduct);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        /** @var ProductExtraTabProduct $extraTabProduct */
        $extraTabProduct = $this->entityManager->getRepository(ProductExtraTabProduct::class)->findOneBy(['id_product' => $data['id_product'], 'id_product_extra_tab' => $data['id_product_extra_tab']]);

        $extraTabProduct->setActive($data['active']);

        foreach ($this->languages as $language) {
            $langId = (int) $language['id_lang'];
            $extraTabDefaultLangByLangId = $extraTabProduct->getProductExtraTabProductLangByLangId($langId);

            $newEntity = false;
            if (null === $extraTabDefaultLangByLangId) {
                $extraTabDefaultLangByLangId = new ProductExtraTabProductLang();
                $lang = $this->langRepository->findOneById($langId);
                $extraTabDefaultLangByLangId->setLang($lang);
                $newEntity = true;
            }

            $extraTabDefaultLangByLangId
                ->setTitle($data['title'][$langId] ?? '')
                ->setContent($data['content'][$langId] ?? '');

            if ($newEntity) {
                $extraTabProduct->addProductExtraTabProductLang($extraTabDefaultLangByLangId);
            }
        }

        $this->entityManager->flush();
    }
}

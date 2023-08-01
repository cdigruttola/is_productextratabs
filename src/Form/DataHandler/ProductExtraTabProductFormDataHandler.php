<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Form\DataHandler;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Oksydan\IsProductExtraTabs\Entity\ProductExtraTabProduct;
use Oksydan\IsProductExtraTabs\Entity\ProductExtraTabProductLang;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use PrestaShopBundle\Entity\Repository\LangRepository;

class ProductExtraTabProductFormDataHandler implements FormDataHandlerInterface
{
    /**
     * @var EntityRepository
     */
    private $extraTabRepository;

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
        EntityRepository $extraTabRepository,
        LangRepository $langRepository,
        EntityManagerInterface $entityManager,
        array $languages
    ) {
        $this->extraTabRepository = $extraTabRepository;
        $this->langRepository = $langRepository;
        $this->entityManager = $entityManager;
        $this->languages = $languages;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        $extraTab = new ProductExtraTabProduct();

        $extraTab->setActive($data['active']);
        $extraTab->setIdProductExtraTab($data['id_product_extra_tab']);
        $extraTab->setIdProduct($data['id_product']);

        foreach ($this->languages as $language) {
            $langId = (int) $language['id_lang'];
            $lang = $this->langRepository->findOneBy(['id' => $langId]);
            $sliderLang = new ProductExtraTabProductLang();

            $sliderLang
                ->setLang($lang)
                ->setTitle($data['title'][$langId] ?? '')
                ->setContent($data['content'][$langId] ?? '');
        }

        $this->entityManager->persist($extraTab);
        $this->entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        /** @var ProductExtraTabProduct $extraTab */
        $extraTab = $this->entityManager->getRepository(ProductExtraTabProduct::class)->findOneBy([$id]);

        $extraTab->setActive($data['active']);

        foreach ($this->languages as $language) {
            $langId = (int) $language['id_lang'];
            $extraTabDefaultLangByLangId = $extraTab->getProductExtraTabProductLangByLangId($langId);

            if (null === $extraTabDefaultLangByLangId) {
                continue;
            }

            $extraTabDefaultLangByLangId
                ->setTitle($data['title'][$langId] ?? '')
                ->setContent($data['content'][$langId] ?? '');
        }

        $this->entityManager->flush();
    }
}

<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Form\DataHandler;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Oksydan\IsProductExtraTabs\Entity\ProductExtraTab;
use Oksydan\IsProductExtraTabs\Entity\ProductExtraTabDefaultLang;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use PrestaShopBundle\Entity\Repository\LangRepository;
use PrestaShopBundle\Entity\Shop;

class ProductExtraTabFormDataHandler implements FormDataHandlerInterface
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
    public function create(array $data): int
    {
        $extraTab = new ProductExtraTab();

        $extraTab->setActive($data['active']);
        $extraTab->setName($data['name'] ?? 'all');
        $extraTab->setPosition($this->extraTabRepository->getHighestPosition() + 1);
        $this->addAssociatedShops($extraTab, $data['shop_association'] ?? null);

        foreach ($this->languages as $language) {
            $langId = (int) $language['id_lang'];
            $lang = $this->langRepository->findOneBy(['id' => $langId]);
            $sliderLang = new ProductExtraTabDefaultLang();

            $sliderLang
                ->setLang($lang)
                ->setTitle($data['title'][$langId] ?? '')
                ->setContent($data['content'][$langId] ?? '');

            $extraTab->addProductExtraTabDefaultLang($sliderLang);
        }

        $this->entityManager->persist($extraTab);
        $this->entityManager->flush();

        return $extraTab->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data): int
    {
        /** @var ProductExtraTab $extraTab */
        $extraTab = $this->entityManager->getRepository(ProductExtraTab::class)->find($id);

        $extraTab->setActive($data['active']);
        $extraTab->setName($data['name']);
        $this->addAssociatedShops($extraTab, $data['shop_association'] ?? null);

        foreach ($this->languages as $language) {
            $langId = (int) $language['id_lang'];
            $extraTabDefaultLangByLangId = $extraTab->getProductExtraTabDefaultLangByLangId($langId);

            if (null === $extraTabDefaultLangByLangId) {
                continue;
            }

            $extraTabDefaultLangByLangId
                ->setTitle($data['title'][$langId] ?? '')
                ->setContent($data['content'][$langId] ?? '');
        }

        $this->entityManager->flush();

        return $extraTab->getId();
    }

    /**
     * @param ProductExtraTab $slider
     * @param array|null $shopIdList
     */
    private function addAssociatedShops(ProductExtraTab & $slider, array $shopIdList = null): void
    {
        $slider->clearShops();

        if (empty($shopIdList)) {
            return;
        }

        foreach ($shopIdList as $shopId) {
            $shop = $this->entityManager->getRepository(Shop::class)->find($shopId);
            $slider->addShop($shop);
        }
    }
}

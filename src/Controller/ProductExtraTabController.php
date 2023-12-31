<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Controller;

use Oksydan\IsProductExtraTabs\Entity\ProductExtraTab;
use Oksydan\IsProductExtraTabs\Entity\ProductExtraTabProduct;
use Oksydan\IsProductExtraTabs\Filter\ProductExtraTabFilters;
use Oksydan\IsProductExtraTabs\Translations\TranslationDomains;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use PrestaShop\PrestaShop\Core\Grid\Position\Exception\PositionDataException;
use PrestaShop\PrestaShop\Core\Grid\Position\Exception\PositionUpdateException;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Entity\Shop;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductExtraTabController extends FrameworkBundleAdminController
{
    /**
     * @var array
     */
    private $languages;

    public function __construct($languages)
    {
        parent::__construct();
        $this->languages = $languages;
    }

    public function index(ProductExtraTabFilters $filters): Response
    {
        $gridFactory = $this->get('oksydan.is_product_extra_tab.grid.product_extra_tab_grid_factory');
        $grid = $gridFactory->getGrid($filters);

        return $this->render('@Modules/is_productextratabs/views/templates/admin/index.html.twig', [
            'translationDomain' => TranslationDomains::TRANSLATION_DOMAIN_ADMIN,
            'grid' => $this->presentGrid($grid),
            'help_link' => false,
        ]);
    }

    public function create(Request $request): Response
    {
        $formDataHandler = $this->get('oksydan.is_product_extra_tab.form.identifiable_object.builder.product_extra_tab_form_builder');
        $form = $formDataHandler->getForm();
        $form->handleRequest($request);

        $formHandler = $this->get('oksydan.is_product_extra_tab.form.identifiable_object.handler.product_extra_tab_form_handler');

        try {
            $result = $formHandler->handle($form);

            if (null !== $result->getIdentifiableObjectId()) {
                $this->addFlash(
                    'success',
                    $this->trans('Successful creation.', 'Admin.Notifications.Success')
                );

                return $this->redirectToRoute('productextratab_controller');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', $this->getErrorMessageForException($e, $this->getErrorMessages($e)));
        }

        return $this->render('@Modules/is_productextratabs/views/templates/admin/form.html.twig', [
            'form' => $form->createView(),
            'title' => $this->trans('Extra Tab', TranslationDomains::TRANSLATION_DOMAIN_ADMIN),
            'help_link' => false,
        ]);
    }

    public function edit(Request $request, int $extraTabId): Response
    {
        $formBuilder = $this->get('oksydan.is_product_extra_tab.form.identifiable_object.builder.product_extra_tab_form_builder');
        $form = $formBuilder->getFormFor((int) $extraTabId);
        $form->handleRequest($request);

        $formHandler = $this->get('oksydan.is_product_extra_tab.form.identifiable_object.handler.product_extra_tab_form_handler');

        try {
            $result = $formHandler->handleFor($extraTabId, $form);

            if (null !== $result->getIdentifiableObjectId()) {
                $this->addFlash(
                    'success',
                    $this->trans('Successful edition.', 'Admin.Notifications.Success')
                );

                return $this->redirectToRoute('productextratab_controller');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', $this->getErrorMessageForException($e, $this->getErrorMessages($e)));
        }

        return $this->render('@Modules/is_productextratabs/views/templates/admin/form.html.twig', [
            'form' => $form->createView(),
            'title' => $this->trans('Extra Tab edit', TranslationDomains::TRANSLATION_DOMAIN_ADMIN),
            'help_link' => false,
        ]);
    }

    public function delete(Request $request, int $extraTabId): Response
    {
        $extraTab = $this->getDoctrine()
            ->getRepository(ProductExtraTab::class)
            ->find($extraTabId);

        if (!empty($extraTab)) {
            $multistoreContext = $this->get('prestashop.adapter.shop.context');
            $entityManager = $this->get('doctrine.orm.entity_manager');

            if ($multistoreContext->isAllShopContext()) {
                $extraTab->clearShops();

                $entityManager->remove($extraTab);
            } else {
                $shopList = $this->getDoctrine()
                    ->getRepository(Shop::class)
                    ->findBy(['id' => $multistoreContext->getContextListShopID()]);

                foreach ($shopList as $shop) {
                    $extraTab->removeShop($shop);
                    $entityManager->flush();
                }

                if (count($extraTab->getShops()) === 0) {
                    $entityManager->remove($extraTab);
                }
            }

            $entityManager->flush();
            $this->addFlash(
                'success',
                $this->trans('Successful deletion.', 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('productextratab_controller');
        }

        $this->addFlash(
            'error',
            $this->trans('Cannot find extraTab %d', TranslationDomains::TRANSLATION_DOMAIN_ADMIN, ['%d' => $extraTabId])
        );

        return $this->redirectToRoute('productextratab_controller');
    }

    /**
     * @param Request $request
     * @param int $extraTabId
     *
     * @return Response
     */
    public function toggleStatus(Request $request, int $extraTabId): Response
    {
        $entityManager = $this->get('doctrine.orm.entity_manager');
        $entity = $entityManager
            ->getRepository(ProductExtraTab::class)
            ->findOneBy(['id' => $extraTabId]);

        if (empty($entity)) {
            $response = [
                'status' => false,
                'message' => sprintf('Entity %d doesn\'t exist', $extraTabId),
            ];
            $errors = [$response];
            $this->flashErrors($errors);

            return $this->redirectToRoute('productextratab_controller');
        }

        try {
            $entity->setActive(!$entity->getActive());
            $entityManager->flush();

            $this->addFlash('success', $this->trans('The status has been successfully updated.', 'Admin.Notifications.Success'));
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'message' => sprintf(
                    'There was an error while updating the status of tab %d: %s',
                    $extraTabId,
                    $e->getMessage()
                ),
            ];
            $errors = [$response];
            $this->flashErrors($errors);
        }

        return $this->redirectToRoute('productextratab_controller');
    }

    /**
     * @param Request $request
     * @param int $extraTabId
     * @param int $productId
     *
     * @return Response
     */
    public function updateProductAction(Request $request, int $extraTabId, int $productId): Response
    {
        $titles = $request->get('titles');
        $contents = $request->get('contents');

        $data = [];
        $data['active'] = (bool) $request->get('active');
        $data['id_product_extra_tab'] = $extraTabId;
        $data['id_product'] = $productId;
        foreach ($titles as $title) {
            $data['title'][(int) $title['languageId']] = $title['value'];
        }
        foreach ($contents as $content) {
            $data['content'][(int) $content['languageId']] = $content['value'];
        }

        /** @var FormDataHandlerInterface $dataHandler */
        $dataHandler = $this->get('oksydan.is_product_extra_tab.form.identifiable_object.data_handler.product_extra_tab_product_form_data_handler');

        $entityManager = $this->get('doctrine.orm.entity_manager');
        $entity = $entityManager
            ->getRepository(ProductExtraTabProduct::class)
            ->findOneBy(['id_product_extra_tab' => $extraTabId, 'id_product' => $productId]);

        try {
            if ($entity) {
                $dataHandler->update($extraTabId, $data);
            } else {
                $dataHandler->create($data);
            }
        } catch (\Exception $e) {
            return $this->json(
                ['message' => $this->getErrorMessageForException($e, $this->getErrorMessages($e))],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $this->json(['message' => $this->trans('Successful update.', 'Admin.Notifications.Success')], Response::HTTP_OK);
    }

    public function updatePositionAction(Request $request): Response
    {
        try {
            $positionsData = [
                'positions' => $request->request->get('positions'),
            ];

            $positionDefinition = $this->get('oksydan.is_product_extra_tab.grid.position_definition');

            $positionUpdateFactory = $this->get('prestashop.core.grid.position.position_update_factory');
            $positionUpdate = $positionUpdateFactory->buildPositionUpdate($positionsData, $positionDefinition);

            $updater = $this->get('prestashop.core.grid.position.doctrine_grid_position_updater');

            $updater->update($positionUpdate);

            $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));
        } catch (PositionDataException|PositionUpdateException $e) {
            $errors = [$e->toArray()];
            $this->flashErrors($errors);
        }

        return $this->redirectToRoute('productextratab_controller');
    }

    /**
     * Provides translated error messages for exceptions
     *
     * @return array
     */
    private function getErrorMessages(\Exception $e): array
    {
        return [
            \Exception::class => [
                $this->trans(
                    'Generic Exception',
                    TranslationDomains::TRANSLATION_DOMAIN_EXCEPTION
                ),
            ],
        ];
    }
}

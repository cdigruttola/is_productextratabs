<?php

declare(strict_types=1);

namespace Oksydan\IsProductExtraTabs\Controller;

use Exception;
use Oksydan\IsProductExtraTabs\Entity\ProductExtraTab;
use Oksydan\IsProductExtraTabs\Filter\ProductExtraTabFilters;
use Oksydan\IsProductExtraTabs\Translations\TranslationDomains;
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
        } catch (Exception $e) {
            $this->addFlash('error', $this->getErrorMessageForException($e, $this->getErrorMessages($e)));
        }

        return $this->render('@Modules/is_productextratabs/views/templates/admin/form.html.twig', [
            'form' => $form->createView(),
            'title' => $this->trans('Extra Tab', TranslationDomains::TRANSLATION_DOMAIN_ADMIN),
            'help_link' => false,
        ]);
    }

    public function edit(Request $request, int $sliderId): Response
    {
        $formBuilder = $this->get('oksydan.is_product_extra_tab.form.identifiable_object.builder.product_extra_tab_form_builder');
        $form = $formBuilder->getFormFor((int) $sliderId);
        $form->handleRequest($request);

        $formHandler = $this->get('oksydan.is_product_extra_tab.form.identifiable_object.handler.product_extra_tab_form_handler');

        try {
            $result = $formHandler->handleFor($sliderId, $form);

            if (null !== $result->getIdentifiableObjectId()) {
                $this->addFlash(
                    'success',
                    $this->trans('Successful edition.', 'Admin.Notifications.Success')
                );

                return $this->redirectToRoute('productextratab_controller');
            }
        } catch (Exception $e) {
            $this->addFlash('error', $this->getErrorMessageForException($e, $this->getErrorMessages($e)));
        }

        return $this->render('@Modules/is_productextratabs/views/templates/admin/form.html.twig', [
            'form' => $form->createView(),
            'title' => $this->trans('Extra Tab edit', TranslationDomains::TRANSLATION_DOMAIN_ADMIN),
            'help_link' => false,
        ]);
    }

    public function delete(Request $request, int $sliderId): Response
    {
        $extraTab = $this->getDoctrine()
            ->getRepository(ProductExtraTab::class)
            ->find($sliderId);

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
            $this->trans('Cannot find extraTab %d', TranslationDomains::TRANSLATION_DOMAIN_ADMIN, ['%d' => $sliderId])
        );

        return $this->redirectToRoute('productextratab_controller');
    }

    /**
     * @param Request $request
     * @param int $sliderId
     *
     * @return Response
     */
    public function toggleStatus(Request $request, int $sliderId): Response
    {
        $entityManager = $this->get('doctrine.orm.entity_manager');
        $entity = $entityManager
            ->getRepository(ProductExtraTab::class)
            ->findOneBy(['id' => $sliderId]);

        if (empty($entity)) {
            return $this->json([
                'status' => false,
                'message' => sprintf('Extra tab %d doesn\'t exist', $sliderId),
            ]);
        }

        try {
            $entity->setActive(!$entity->getActive());
            $entityManager->flush();

            $response = [
                'status' => true,
                'message' => $this->trans('The status has been successfully updated.', 'Admin.Notifications.Success'),
            ];
        } catch (\Exception $e) {
            $response = [
                'status' => false,
                'message' => sprintf(
                    'There was an error while updating the status of slide %d: %s',
                    $sliderId,
                    $e->getMessage()
                ),
            ];
        }

        return $this->json($response);
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
    private function getErrorMessages(Exception $e): array
    {
        return [
            Exception::class => [
                $this->trans(
                    'Generic Exception',
                    TranslationDomains::TRANSLATION_DOMAIN_EXCEPTION
                ),
            ],
        ];
    }
}

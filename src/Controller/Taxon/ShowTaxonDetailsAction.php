<?php

namespace Sylius\ShopApiPlugin\Controller\Taxon;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Sylius\ShopApiPlugin\Factory\TaxonDetailsViewFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ShowTaxonDetailsAction
{
    /**
     * @var TaxonRepositoryInterface
     */
    private $taxonRepository;

    /**
     * @var ViewHandlerInterface
     */
    private $viewHandler;

    /**
     * @var TaxonDetailsViewFactoryInterface
     */
    private $taxonViewFactory;

    public function __construct(
        TaxonRepositoryInterface $taxonRepository,
        ViewHandlerInterface $viewHandler,
        TaxonDetailsViewFactoryInterface $taxonViewFactory
    ) {
        $this->taxonRepository = $taxonRepository;
        $this->viewHandler = $viewHandler;
        $this->taxonViewFactory = $taxonViewFactory;
    }

    public function __invoke(Request $request): Response
    {
        $taxonSlug = $request->attributes->get('slug');
        $locale = $request->query->get('locale');

        $taxon = $this->taxonRepository->findOneBySlug($taxonSlug, $locale);

        if (null === $taxon) {
            throw new NotFoundHttpException(sprintf('Taxon with slug %s has not been found in %s locale.', $taxonSlug, $locale));
        }

        return $this->viewHandler->handle(View::create($this->taxonViewFactory->create($taxon, $locale), Response::HTTP_OK));
    }
}

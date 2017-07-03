<?php

namespace spec\Sylius\ShopApiPlugin\Factory;

use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\ShopApiPlugin\Factory\TaxonDetailsViewFactoryInterface;
use Sylius\ShopApiPlugin\Factory\TaxonViewFactoryInterface;
use PhpSpec\ObjectBehavior;
use Sylius\ShopApiPlugin\View\TaxonDetailsView;
use Sylius\ShopApiPlugin\View\TaxonView;

final class TaxonDetailsViewFactorySpec extends ObjectBehavior
{
    function let(TaxonViewFactoryInterface $taxonViewFactory)
    {
        $this->beConstructedWith($taxonViewFactory);
    }

    function it_is_taxon_view_factory()
    {
        $this->shouldImplement(TaxonDetailsViewFactoryInterface::class);
    }

    function it_creates_taxon_view(
        TaxonInterface $taxon,
        TaxonInterface $parentTaxon,
        TaxonViewFactoryInterface $taxonViewFactory
    ) {
        $taxon->getParent()->willReturn($parentTaxon);
        $taxonViewFactory->create($taxon, 'en_GB')->willReturn(new TaxonView());
        $taxonViewFactory->create($parentTaxon, 'en_GB')->willReturn(new TaxonView());

        $taxonView = new TaxonDetailsView();
        $taxonView->self = new TaxonView();
        $taxonView->parentTree = new TaxonView();
        $taxonView->parentTree->children[] = new TaxonView();

        $this->create($taxon, 'en_GB')->shouldBeLike($taxonView);
    }
}

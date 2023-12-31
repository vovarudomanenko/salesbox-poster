<?php

namespace App\Poster\Transformers;


use App\Poster\Models\PosterProductModification;
use App\Poster\Utils;
use App\Salesbox\Facades\SalesboxStore;
use App\Salesbox\Models\SalesboxOfferV4;
use function config;

class PosterProductModificationAsSalesboxOffer {

    public $productModification;
    public function __construct(PosterProductModification $productModification) {
        $this->productModification = $productModification;
    }

    public function transform(): SalesboxOfferV4
    {
        $offer = new SalesboxOfferV4([]);
        $this->updateFrom($offer);

        return $offer;
    }

    public function updateFrom(SalesboxOfferV4 $offer): SalesboxOfferV4 {
        $product = $this->productModification->getProduct();

        $offer->setExternalId($product->getProductId());
        $offer->setModifierId($this->productModification->getModificatorId());
        $offer->setAvailable($this->productModification->isVisible());
        $offer->setPrice($this->productModification->getFirstPrice());
        $offer->setStockType('endless');
        $offer->setUnits('pc');
        $offer->setCategories([]);
        $offer->setPhotos([]);
        $offer->setDescriptions([]);
        $offer->setNames([
            [
                'name' => $product->getProductName() . ' ' . $this->productModification->getModificatorName(),
                'lang' => config('salesbox.lang')
            ]
        ]);

        if($product->hasPhoto()) {
            $offer->setPreviewURL(Utils::poster_upload_url($product->getPhoto()));
        }

        if($product->hasPhotoOrigin()) {
            $offer->setOriginalURL(Utils::poster_upload_url($product->getPhotoOrigin()));
        }

        if(
            $product->hasPhotoOrigin() &&
            $product->hasPhoto()
        ) {
            $offer->setPhotos([
                [
                    'url' => Utils::poster_upload_url($product->getPhotoOrigin()),
                    'previewURL' => Utils::poster_upload_url($product->getPhoto()),
                    'order' => 0,
                    'type' => 'image',
                    'resourceType' => 'image'
                ]
            ]);
        }

        $category = SalesboxStore::findCategoryByExternalId($product->getMenuCategoryId());

        if ($category) {
            $offer->setCategories([$category->getId()]);
        }
        return clone $offer;
    }

}

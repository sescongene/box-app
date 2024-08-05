<?php

namespace App\Services;

use App\Models\Box;
use Exception;

class BoxSelector
{
    public function selectBoxes(array $products)
    {
        // Get all boxes and sort them by volume
        $boxes = Box::all()->sortBy(function ($box) {
            return $box->length * $box->width * $box->height;
        });

        $usedBoxes = [];
        $unfitProducts = [];
        foreach ($products as $product) {
            $productVolume = $product['length'] * $product['width'] * $product['height'];
            $productWeight = $product['weight'];
            $quantity = $product['quantity'];

            while ($quantity > 0) {
                $boxFound = false;
                foreach ($boxes as $box) {
                    $boxVolume = $box->length * $box->width * $box->height;
                    $boxWeightLimit = $box->weight_limit;

                    if ($product['length'] < $box->length && $product['width'] < $box->width && $product['height'] < $box->height) {
                        $maxQuantityByVolume = floor($boxVolume / $productVolume);
                        $maxQuantityByWeight = floor($boxWeightLimit / $productWeight);
                        $maxQuantityInBox = min($maxQuantityByVolume, $maxQuantityByWeight);

                        if ($maxQuantityInBox > 0) {
                            $quantityToFit = min($quantity, $maxQuantityInBox);
                            $usedBoxes[$box->name][] = array_merge($product, ['quantity' => $quantityToFit]);
                            $quantity -= $quantityToFit;
                            $boxFound = true;
                            break;
                        }
                    }
                }

                if (!$boxFound) {
                    $unfitProducts[] = array_merge($product, ['quantity' => $quantity]);
                    break;
                }
            }
        }

        return ['usedBoxes' => $usedBoxes, 'unfitProducts' => $unfitProducts];
    }
}

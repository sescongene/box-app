<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubmitProductRequest;
use App\Models\Product;
use App\Services\BoxSelector;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Undocumented function
     *
     * @return view
     */
    public function index()
    {
        return view('products.index');
    }

    /**
     * checkBoxes function
     *
     * @param SubmitProductRequest $request
     * @param BoxSelector $boxSelector
     * @return void
     */
    public function checkBoxes(SubmitProductRequest $request, BoxSelector $boxSelector)
    {
        try {
            $result = $boxSelector->selectBoxes($request->products);
            $selectedBoxes = $result['usedBoxes'];
            $unfitProducts = $result['unfitProducts'];

            $productsInBoxCount = [];

            foreach ($selectedBoxes as $boxName => $productsInBox) {
                foreach ($productsInBox as $product) {
                    if (!isset($productsInBoxCount[$product['name']])) {
                        $productsInBoxCount[$product['name']] = 0;
                    }
                    $productsInBoxCount[$product['name']]++;
                }
            }

            return redirect()->route('products.index')->with([
                'selectedBoxes' => $selectedBoxes,
                'productsInBoxCount' => $productsInBoxCount,
                'unfitProducts' => $unfitProducts
            ]);
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', $e->getMessage());
        }
    }
}

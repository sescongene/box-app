<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BoxSelector;

class BoxCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:box-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = [
            ['name' => 'Product A', 'length' => 30, 'width' => 25, 'height' => 20, 'weight' => 10, 'quantity' => 10],
            ['name' => 'Product B', 'length' => 40, 'width' => 25, 'height' => 30, 'weight' => 10, 'quantity' => 10],
            ['name' => 'Product C', 'length' => 220, 'width' => 225, 'height' => 220, 'weight' => 101, 'quantity' => 10],
            // Add more products as needed
        ];
        
        $boxSelector = new BoxSelector();
        try {
            $result = $boxSelector->selectBoxes($products);
            $selectedBoxes = $result['usedBoxes'];
            $unfitProducts = $result['unfitProducts'];

            echo 'Number of boxes used: ' . count($selectedBoxes) . "\n";
            $productsInBoxCount = [];
            
            foreach ($selectedBoxes as $boxName => $productsInBox) {
                $box = \App\Models\Box::where('name', $boxName)->first();
                echo 'Selected Box: ' . $boxName . ' (Dimensions: ' . $box->length . 'x' . $box->width . 'x' . $box->height . ")\n";
                foreach ($productsInBox as $product) {
                    echo '  Product: ' . json_encode($product) . "\n";
                    if (!isset($productsInBoxCount[$product['name']])) {
                        $productsInBoxCount[$product['name']] = 0;
                    }
                    $productsInBoxCount[$product['name']]++;
                }
            }

            echo "\nTotal number of boxes per product:\n";
            foreach ($productsInBoxCount as $productName => $boxCount) {
                echo '  ' . $productName . ': ' . $boxCount . " boxes\n";
            }

            if (!empty($unfitProducts)) {
                echo "\nProducts that couldn't fit into any box:\n";
                foreach ($unfitProducts as $product) {
                    echo '  Product: ' . json_encode($product) . "\n";
                }
            }
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
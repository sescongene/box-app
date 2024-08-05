<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\ProductController;
use App\Http\Requests\SubmitProductRequest;
use App\Services\BoxSelector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\Models\Box;
use Mockery;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'BoxSeeder']);
    }

    public function testCheckBoxesValidInput()
    {
        $products = [
            ['name' => 'Product A', 'length' => 10, 'width' => 10, 'height' => 5, 'weight' => 2, 'quantity' => 5],
            ['name' => 'Product B', 'length' => 20, 'width' => 15, 'height' => 10, 'weight' => 5, 'quantity' => 3],
        ];

        $request = new SubmitProductRequest(['products' => $products]);
        $boxSelector = Mockery::mock(BoxSelector::class);
        $boxSelector->shouldReceive('selectBoxes')->andReturn([
            'usedBoxes' => [
                'BOXA' => [$products[0]],
                'BOXB' => [$products[1]],
            ],
            'unfitProducts' => [],
        ]);

        $controller = new ProductController();
        $response = $controller->checkBoxes($request, $boxSelector);

        // Manually check the response properties
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('products.index'), $response->headers->get('Location'));
        $this->assertTrue(session()->has('selectedBoxes'));
        $this->assertTrue(session()->has('productsInBoxCount'));
    }

    public function testCheckBoxesInvalidInput()
    {
        $products = [
            ['name' => 'Product C', 'length' => 100, 'width' => 100, 'height' => 100, 'weight' => 100, 'quantity' => 1],
        ];

        $request = new SubmitProductRequest(['products' => $products]);
        $boxSelector = Mockery::mock(BoxSelector::class);
        $boxSelector->shouldReceive('selectBoxes')->andReturn([
            'usedBoxes' => [],
            'unfitProducts' => $products,
        ]);

        $controller = new ProductController();
        $response = $controller->checkBoxes($request, $boxSelector);

        // Manually check the response properties
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('products.index'), $response->headers->get('Location'));
        $this->assertTrue(session()->has('selectedBoxes'));
        $this->assertTrue(session()->has('productsInBoxCount'));
    }

    public function testCheckBoxesEdgeCase()
    {
        $products = [
            ['name' => 'Product D', 'length' => 20, 'width' => 15, 'height' => 10, 'weight' => 5, 'quantity' => 1],
        ];

        $request = new SubmitProductRequest(['products' => $products]);
        $boxSelector = Mockery::mock(BoxSelector::class);
        $boxSelector->shouldReceive('selectBoxes')->andReturn([
            'usedBoxes' => [
                'BOXA' => [$products[0]],
            ],
            'unfitProducts' => [],
        ]);

        $controller = new ProductController();
        $response = $controller->checkBoxes($request, $boxSelector);

        // Manually check the response properties
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('products.index'), $response->headers->get('Location'));
        $this->assertTrue(session()->has('selectedBoxes'));
        $this->assertTrue(session()->has('productsInBoxCount'));
    }

    public function testCheckBoxesWithTenProducts()
    {
        $products = [
            ['name' => 'Product 1', 'length' => 10, 'width' => 10, 'height' => 5, 'weight' => 2, 'quantity' => 1],
            ['name' => 'Product 2', 'length' => 20, 'width' => 15, 'height' => 10, 'weight' => 5, 'quantity' => 1],
            ['name' => 'Product 3', 'length' => 15, 'width' => 10, 'height' => 5, 'weight' => 3, 'quantity' => 1],
            ['name' => 'Product 4', 'length' => 25, 'width' => 20, 'height' => 15, 'weight' => 7, 'quantity' => 1],
            ['name' => 'Product 5', 'length' => 30, 'width' => 25, 'height' => 20, 'weight' => 10, 'quantity' => 1],
            ['name' => 'Product 6', 'length' => 35, 'width' => 30, 'height' => 25, 'weight' => 12, 'quantity' => 1],
            ['name' => 'Product 7', 'length' => 40, 'width' => 35, 'height' => 30, 'weight' => 15, 'quantity' => 1],
            ['name' => 'Product 8', 'length' => 45, 'width' => 40, 'height' => 35, 'weight' => 18, 'quantity' => 1],
            ['name' => 'Product 9', 'length' => 50, 'width' => 45, 'height' => 40, 'weight' => 20, 'quantity' => 1],
            ['name' => 'Product 10', 'length' => 55, 'width' => 50, 'height' => 45, 'weight' => 25, 'quantity' => 1],
        ];

        $request = new SubmitProductRequest(['products' => $products]);
        $boxSelector = Mockery::mock(BoxSelector::class);
        $boxSelector->shouldReceive('selectBoxes')->andReturn([
            'usedBoxes' => [
                'BOXA' => [$products[0], $products[1]],
                'BOXB' => [$products[2], $products[3]],
                'BOXC' => [$products[4], $products[5]],
                'BOXD' => [$products[6], $products[7]],
                'BOXE' => [$products[8], $products[9]],
            ],
            'unfitProducts' => [],
        ]);

        $controller = new ProductController();
        $response = $controller->checkBoxes($request, $boxSelector);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('products.index'), $response->headers->get('Location'));
        $this->assertTrue(session()->has('selectedBoxes'));
        $this->assertTrue(session()->has('productsInBoxCount'));
    }
}

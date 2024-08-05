
# BoxApp
This laravel app is very basic upto the setup of the project. It only requires database connection to the database to store the boxes and the core function is stored in a separate service in `app\Services\BoxSelector.php` which holds the algorithm to identify the smallest box.

## Installation
The setup is pretty simple to start with. It only requires two databases 1 for the main app and 1 for the testing.

### Requirements 
```
php8.3
composer
mysql
```
1. Create app environment `.env` and testing environment `.env.testing`.
2. Populate the database connection in the environments

```
DB_CONNECTION=mysql
DB_HOST={{database_host}}
DB_PORT={{database_port}}
DB_DATABASE={{database_name}}
DB_USERNAME={{database_username}}
DB_PASSWORD={{database_password}}
```
3.  Execute this command
```bash
composer install
php artisan migrate
php artisan key:generate
php artisan serve
```
4. And visit the url provided by the php artisan serve
#### Testing
1. Once the `.env.testing` is populated execute
```
php artisan test
```



## Code Overview: Packing Products into Boxes

This section of code handles the task of fitting a specified quantity of a product into available boxes based on the product's dimensions and weight. The process continues until all products are either packed or determined to be unsuitable for any available box.

#### Initialization and Loop Process

-   The packing process runs inside a `while` loop, which continues as long as there are products left to pack (`$quantity > 0`).
-   At the beginning of each iteration, a flag `$boxFound` is set to `false` to track whether a suitable box has been identified for the current product.

#### Iterating Through Available Boxes

-   A `foreach` loop iterates over each box in the `$boxes` array.
-   For each box, the volume (`$boxVolume`) and weight capacity (`$boxWeightLimit`) are calculated.

#### Checking if the Product Fits

-   The code checks if the product's dimensions (length, width, height) fit within the box's dimensions.
-   If the product fits:
    -   The maximum quantity of the product that can be packed based on volume (`$maxQuantityByVolume`) and weight (`$maxQuantityByWeight`) is calculated.
    -   The smaller of these two values (`$maxQuantityInBox`) is used to determine how much of the product can be packed into the box.

#### Packing the Product into the Box

-   If the box can accommodate at least one unit of the product (`$maxQuantityInBox > 0`):
    -   The code determines the quantity to fit in the current box (`$quantityToFit`), which is the lesser of the remaining quantity and the box’s capacity.
    -   This quantity is then recorded in the `$usedBoxes` array under the current box's identifier.
    -   The remaining quantity is updated to reflect what has been packed.
    -   The `$boxFound` flag is set to `true`, and the loop breaks to start packing the next product quantity.

#### Handling Products that Cannot Fit

-   If no suitable box is found for the product (`$boxFound` remains `false`):
    -   The remaining quantity of the product is added to the `$unfitProducts` array, signaling that it could not be packed.
    -   The loop then exits, concluding the packing attempt for the current product.

#### Key Variables

-   **$quantity**: The remaining quantity of the product that needs to be packed.
-   **$boxFound**: A flag that indicates whether a suitable box was found during the current iteration.
-   **$boxes**: An array of all available boxes.
-   **$boxVolume**: The calculated volume of the current box.
-   **$boxWeightLimit**: The weight capacity of the current box.
-   **$product**: The product that is being packed.
-   **$productVolume**: The volume of the product.
-   **$productWeight**: The weight of the product.
-   **$maxQuantityByVolume**: The maximum quantity of the product that can fit into the box based on volume.
-   **$maxQuantityByWeight**: The maximum quantity of the product that can fit into the box based on weight.
-   **$maxQuantityInBox**: The maximum quantity that can fit into the box, determined by the lesser of the volume and weight calculations.
-   **$quantityToFit**: The quantity of the product that will be packed into the current box.
-   **$usedBoxes**: An array that tracks which products are packed into which boxes.
-   **$unfitProducts**: An array that tracks products that could not be packed into any available box.


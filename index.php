<?php

interface SellerInterface
{
    /**
     * @return array
     */
    public function setPricing(): array;

    /**
     * @param  $product
     *
     * @return array
     */
    public function scan($product): array;

    /**
     * @return mixed
     */
    public function total(): float;
}

class Terminal implements SellerInterface
{
    private $pricing = [];
    private $products = [];

    /**
     * @return array
     */
    public function setPricing(): array
    {
        $pricing = [
            'A' => [
                'base_price'       => 2,
                'products_count'   => 4,
                'discounted_price' => 7,
            ],
            'B' => [
                'base_price'       => 12,
                'products_count'   => null,
                'discounted_price' => null,
            ],
            'C' => [
                'base_price'       => 1.25,
                'products_count'   => 6,
                'discounted_price' => 6,
            ],
            'D' => [
                'base_price'       => 0.15,
                'products_count'   => null,
                'discounted_price' => null,
            ],
        ];

        $this->pricing = $pricing;

        return $pricing;
    }

    /**
     * @param $product
     *
     * @return array
     */
    public function scan($product): array
    {
        if (key_exists($product, $this->pricing)) {
            if (isset($this->products[$product])) {
                $this->products[$product]++;
            } else {
                $this->products[$product] = 1;
            }
        } else {
            echo "We don't sell this product ((\n";
        }

        return $this->products;
    }

    /**
     * @return float
     */
    public function total(): float
    {
        $total = 0;

        foreach ($this->products as $product => $count) {
            if (is_null($this->pricing[$product]['products_count'])) {
                $price = $this->pricing[$product]['base_price'] * $count;
            } else {
                $price = ((int) ($count / $this->pricing[$product]['products_count']) * $this->pricing[$product]['discounted_price']) + ($count % $this->pricing[$product]['products_count']) * $this->pricing[$product]['base_price'];
            }

            $total += $price;
        }

        return $total;
    }
}

$terminal = new Terminal();
$terminal->setPricing();
echo "Enter the products names one at a time. Just press 'Enter' after the last one. \n";

while (true) {
    $title = strtoupper(readline());
    if (empty($title)) {
        echo "Total price: " . $terminal->total() . "\n";
        break;
    }

    $list = $terminal->scan($title);
    echo "Product list:\n";
    foreach ($list as $product => $count) {
        echo "$product: $count\n";
    }

    readline_add_history($title);
}

echo "Product list: " . implode(readline_list_history());
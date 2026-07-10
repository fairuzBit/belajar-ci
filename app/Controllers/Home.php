<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Home extends BaseController
{
    protected $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index(): string
    {
        $products = $this->productModel->findAll();

        $diskon = get_today_discount();
        $discountNominal = $diskon ? $diskon['nominal'] : 0;

        foreach ($products as &$product) {
            $product['harga_diskon'] = $product['harga'] - $discountNominal;
            if ($product['harga_diskon'] < 0) {
                $product['harga_diskon'] = 0;
            }
        }

        return view('v_home', [
            'products' => $products,
            'discount' => $diskon
        ]);
    }
}

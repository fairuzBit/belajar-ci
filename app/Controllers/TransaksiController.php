<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\ProductModel;
use App\Services\RajaOngkirService;

class TransaksiController extends BaseController
{
    protected $cart;
    protected $transactionModel;
    protected $transactionDetailModel;
    protected $productModel;

    public function __construct()
    {
        $this->cart = new \MuratDemirel\Cart\Cart();
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
        $this->productModel = new ProductModel();
        helper('form');
    }

    public function index(): string
    {
        $diskon = get_today_discount();
        $discountNominal = $diskon ? $diskon['nominal'] : 0;

        $data = [
            'items' => $this->cart->content(),
            'total' => $this->cart->total(),
            'discount' => $diskon,
            'discount_nominal' => $discountNominal
        ];
        return view('v_keranjang', $data);
    }

    public function add()
    {
        $id = $this->request->getPost('id');
        $product = $this->productModel->find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan');
        }

        $diskon = get_today_discount();
        $discountNominal = $diskon ? $diskon['nominal'] : 0;
        $price = $product['harga'] - $discountNominal;
        if ($price < 0) {
            $price = 0;
        }

        $this->cart->add($id, $product['nama'], 1, $price);

        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang');
    }

    public function remove($id)
    {
        $this->cart->remove($id);
        return redirect()->to('keranjang')->with('success', 'Produk dihapus dari keranjang');
    }

    public function cart_clear()
    {
        $this->cart->destroy();
        return redirect()->to('keranjang')->with('success', 'Keranjang dikosongkan');
    }

    public function checkout()
    {
        $diskon = get_today_discount();
        $discountNominal = $diskon ? $diskon['nominal'] : 0;

        $data = [
            'items' => $this->cart->content(),
            'total' => $this->cart->total(),
            'discount' => $diskon,
            'discount_nominal' => $discountNominal
        ];
        return view('v_checkout', $data);
    }

    public function destinations()
    {
        $search = $this->request->getGet('q');
        $service = new RajaOngkirService();
        $response = $service->getDestination($search);

        $results = [];
        $data = $response['data'] ?? [];
        foreach ($data as $item) {
            $results[] = [
                'id' => $item['id'],
                'text' => $item['label']
            ];
        }

        return $this->response->setJSON([
            'results' => $results
        ]);
    }

    public function costs()
    {
        $origin = '64999';
        $destination = $this->request->getGet('destination');
        $weight = '1000';
        $courier = 'jne';

        $service = new RajaOngkirService();
        $response = $service->getCost($origin, $destination, $weight, $courier);

        $results = [];
        $data = $response['data'] ?? [];
        foreach ($data as $item) {
            $results[] = [
                'service' => $item['service'],
                'description' => $item['description'],
                'cost' => $item['cost'],
                'etd' => $item['etd']
            ];
        }

        return $this->response->setJSON($results);
    }

    public function buy()
    {
        $cartItems = $this->cart->content();
        if (empty($cartItems)) {
            return redirect()->back();
        }

        $diskon = get_today_discount();
        $discountNominal = $diskon ? $diskon['nominal'] : 0;

        $db = \Config\Database::connect();
        $db->transStart();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['qty'] * $item['price'];
        }

        $ongkir = (int) $this->request->getPost('ongkir');

        $transaction = [
            'username' => $this->request->getPost('username'),
            'alamat' => $this->request->getPost('alamat'),
            'ongkir' => $ongkir,
            'total_harga' => $subtotal + $ongkir,
            'status' => 0,
        ];

        if (!$this->transactionModel->insert($transaction)) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal membuat transaksi');
        }

        $transactionId = $this->transactionModel->getInsertID();

        foreach ($cartItems as $item) {
            $this->transactionDetailModel->insert([
                'transaction_id' => $transactionId,
                'product_id' => $item['productId'],
                'jumlah' => $item['qty'],
                'diskon' => $discountNominal,
                'subtotal_harga' => $item['qty'] * $item['price']
            ]);
        }

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->with('error', 'Gagal membuat transaksi');
        }

        $this->cart->destroy();
        return redirect()->to(base_url());
    }

    public function history()
    {
        $username = session()->get('username');
        $transactions = $this->transactionModel->where('username', $username)->findAll();
        $transactionIds = array_column($transactions, 'id');
        $products = $this->transactionDetailModel->getProductsByTransactionIds($transactionIds);
        $data = [
            'username' => $username,
            'transactions' => $transactions,
            'products' => $products
        ];
        return view('v_history', $data);
    }
}

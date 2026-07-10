<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class TransactionAdminController extends BaseController
{
    protected $transactionModel;
    protected $transactionDetailModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
    }

    public function index(): string
    {
        $transactions = $this->transactionModel->orderBy('created_at', 'DESC')->findAll();
        $transactionIds = array_column($transactions, 'id');
        $products = $this->transactionDetailModel->getProductsByTransactionIds($transactionIds);

        return view('pembelian/index', [
            'transactions' => $transactions,
            'products' => $products
        ]);
    }

    public function status($id)
    {
        $transaction = $this->transactionModel->find($id);
        if (!$transaction) {
            return redirect('pembelian')->with('error', 'Transaksi tidak ditemukan');
        }

        $newStatus = $transaction['status'] == 1 ? 0 : 1;
        $this->transactionModel->update($id, ['status' => $newStatus]);

        return redirect('pembelian')->with('success', 'Status berhasil diubah');
    }
}

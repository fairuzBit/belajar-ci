<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\DiscountModel;

class DiscountController extends BaseController
{
    protected $discountModel;

    public function __construct()
    {
        $this->discountModel = new DiscountModel();
        helper('form');
    }

    public function index(): string
    {
        return view('diskon/index', [
            'discounts' => $this->discountModel->orderBy('tanggal', 'ASC')->findAll()
        ]);
    }

    public function create()
    {
        $rules = [
            'tanggal' => 'required|valid_date[Y-m-d]|is_unique[discount.tanggal]',
            'nominal' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $this->discountModel->insert([
            'tanggal' => $this->request->getPost('tanggal'),
            'nominal' => $this->request->getPost('nominal')
        ]);

        return redirect('diskon')->with('success', 'Diskon berhasil ditambahkan');
    }

    public function edit($id)
    {
        $discount = $this->discountModel->find($id);
        if (!$discount) {
            return redirect('diskon')->with('error', 'Diskon tidak ditemukan');
        }

        $rules = [
            'nominal' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $this->discountModel->update($id, [
            'nominal' => $this->request->getPost('nominal')
        ]);

        return redirect('diskon')->with('success', 'Diskon berhasil diubah');
    }

    public function delete($id)
    {
        $this->discountModel->delete($id);
        return redirect('diskon')->with('success', 'Diskon berhasil dihapus');
    }
}

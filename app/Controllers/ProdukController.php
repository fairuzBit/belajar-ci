<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ProdukController extends BaseController
{
    public function index()
    {
        return view('v_produk');
    }

    public function simpan()
    {
        $nama = $this->request->getPOST('nama_produk');
        return redirect()->to('/produk');
    }
    public function update($id)
    {
        $dataBaru = $this->request->getRawInput();
    }

    public function hapus($id) {}
}

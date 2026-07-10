<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DiscountModel;

class DiscountController extends ResourceController
{
    protected $modelName = 'App\Models\DiscountModel';
    protected $format    = 'json';

    private $token;

    public function __construct()
    {
        $this->token = env('MY_API_KEY');
    }

    private function authenticate()
    {
        $header = $this->request->getHeaderLine('Authorization');
        if (empty($header)) {
            return false;
        }
        if (!preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
            return false;
        }
        return $matches[1] === $this->token;
    }

    private function unauthorized()
    {
        return $this->failUnauthorized('Unauthorized');
    }

    public function index()
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $page = $this->request->getGet('page') ?? 1;
        $perPage = $this->request->getGet('per_page') ?? 10;

        $discounts = $this->model->orderBy('tanggal', 'ASC')
            ->paginate($perPage, 'default', $page);

        $total = $this->model->pager->getTotal();

        return $this->respond([
            'data' => $discounts,
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'total_pages' => ceil($total / $perPage),
            ]
        ]);
    }

    public function show($id = null)
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $data = $this->model->find($id);
        if (!$data) {
            return $this->failNotFound('Discount not found');
        }

        return $this->respond(['data' => $data]);
    }

    public function create()
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $rules = [
            'tanggal' => 'required|valid_date[Y-m-d]|is_unique[discount.tanggal]',
            'nominal' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $id = $this->model->insert([
            'tanggal' => $this->request->getJSON()->tanggal,
            'nominal' => $this->request->getJSON()->nominal,
        ]);

        return $this->respondCreated(['id' => $id]);
    }

    public function update($id = null)
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $data = $this->model->find($id);
        if (!$data) {
            return $this->failNotFound('Discount not found');
        }

        $rules = [
            'nominal' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $this->model->update($id, [
            'nominal' => $this->request->getJSON()->nominal
        ]);

        return $this->respondUpdated(['id' => $id]);
    }

    public function delete($id = null)
    {
        if (!$this->authenticate()) {
            return $this->unauthorized();
        }

        $data = $this->model->find($id);
        if (!$data) {
            return $this->failNotFound('Discount not found');
        }

        $this->model->delete($id);

        return $this->respondDeleted(['id' => $id]);
    }
}

<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run()
    {
        $data = [];
        $startDate = date("Y-m-d");
        for ($i = 0; $i < 10; $i++) {
            $date = date("Y-m-d", strtotime($startDate . " +{$i} days"));
            $data[] = [
                'tanggal'    => $date,
                'nominal'    => rand(5000, 50000),
                'created_at' => date("Y-m-d H:i:s"),
            ];
        }
        $this->db->table('discount')->insertBatch($data);
    }
}

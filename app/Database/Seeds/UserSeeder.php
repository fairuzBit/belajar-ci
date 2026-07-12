<?php
namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;
class UserSeeder extends Seeder
{
    public function run()
    {
        // Default admin user for easy login (checks if already exists to prevent duplicate entry error)
        $existingAdmin = $this->db->table('user')->where('username', 'admin123')->get()->getRow();
        if (!$existingAdmin) {
            $defaultAdmin = [
                'username'   => 'admin123',
                'email'      => 'admin@example.com',
                'password'   => password_hash('1234567', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'created_at' => date("Y-m-d H:i:s"),
            ];
            $this->db->table('user')->insert($defaultAdmin);
        }

        $faker = \Faker\Factory::create('id_ID');
        for ($i = 0; $i < 10; $i++) {
            $data = [
                'username'   => $faker->userName(),
                'email'      => $faker->email(),
                'password'   => password_hash('1234567', PASSWORD_DEFAULT),
                'role'       => $faker->randomElement(['admin', 'guest']),
                'created_at' => date("Y-m-d H:i:s"),
            ];
            $this->db->table('user')->insert($data);
        }
    }
}

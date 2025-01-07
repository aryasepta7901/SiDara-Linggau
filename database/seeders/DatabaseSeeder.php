<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Kecamatan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Kecamatan::insert(
            [
                [
                    'id' => '1674011',
                    'kecamatan' => 'Lubuk Linggau Barat I',
                ],
                [
                    'id' => '1674',
                    'kecamatan' => 'BPS Kota Lubuk Linggau',
                ],
                [
                    'id' => '1674012',
                    'kecamatan' => 'Lubuk Linggau Barat II',
                ],
                [
                    'id' => '1674021',
                    'kecamatan' => 'Lubuk Linggau Selatan I',
                ],
                [
                    'id' => '1674022',
                    'kecamatan' => 'Lubuk Linggau Selatan II',
                ],
                [
                    'id' => '1674031',
                    'kecamatan' => 'Lubuk Linggau Timur I',
                ],
                [
                    'id' => '1674032',
                    'kecamatan' => 'Lubuk Linggau Timur II',
                ],
                [
                    'id' => '1674041',
                    'kecamatan' => 'Lubuk Linggau Utara I',
                ],
                [
                    'id' => '1674042',
                    'kecamatan' => 'Lubuk Linggau Utara II',
                ],
            ]
        );
        User::insert([
            [
                'id' => '199811052021041001',
                'name' => 'Raden Mulia',
                'email' => 'raden.mulia@bps.go.id',
                'password' => '$2y$10$64wtuBDKS8A5b4Od.UMuBeCSGeg9dzr8XNYAnJU.MQYQz9/HwTzxK', 
                'role' => 'PCL',
                'kec_id' => '1674011'
            ],
            [
                'id' => '198408242008012009',
                'name' => ' Al Maratul Sholihah',
                'email' => 'almaratul@bps.go.id',
                'password' => '$2y$10$64wtuBDKS8A5b4Od.UMuBeCSGeg9dzr8XNYAnJU.MQYQz9/HwTzxK', 
                'role' => 'PML', //admin BPS
                'kec_id' => '1674'

            ],
            [
                'id' => '200109072023101003',
                'name' => 'Muhammad Arya Septa Kovitra',
                'email' => 'arya.septa@bps.go.id',
                'password' => '$2y$10$64wtuBDKS8A5b4Od.UMuBeCSGeg9dzr8XNYAnJU.MQYQz9/HwTzxK', 
                'role' => 'AD', //admin Dinas
                'kec_id' => '1674',
            ],
        ]);
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Menu;
use Illuminate\Console\Command;

class GenMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:gen-menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $menus = [
            'Teh' => [
                'Teh Manis Panas/Dingin' => 4000,
                'Teh Susu Panas/Dingin' => 4000,
                'Teh Tarik Panas/Dingin' => 4000,
                'Teh Botol' => 4000,
            ],
            'Minuman Lainnya' => [
                'Kopi Hitam Panas/Dingin' => 4000,
                'Kopi Susu Panas/Dingin' => 4000,
                'Kopi Jahe Panas/Dingin' => 4000,
                'Susu Putih Panas/Dingin' => 4000,
                'Susu Coklat Panas/Dingin' => 4000,
            ],
            'Makanan' => [
                'Indomie ( telur)' => 7000,
                'Indomie polos' => 7000,
                'Roti Bakar' => 70000
            ],
            'Cemilan' => [
                'Tahu' => 1000,
                'Kerupuk' => 7000,
                'Cireng' => 70000
            ]
        ];

        foreach ($menus as $key => $menu) {
            foreach ($menu as $km => $m) {
                Menu::updateOrCreate([
                    'nama_menu' => $km,
                ], [
                    'nama_menu' => $km,
                    'harga' => $m,
                    'kategori_menu' => $key,
                    'status' => 'Aktif'
                ]);
            }
        }
    }
}

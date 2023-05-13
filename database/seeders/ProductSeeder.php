<?php

namespace Database\Seeders;

use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adjective = ['Small', 'Ergonomic', 'Rustic', 'Intelligent', 'Gorgeous', 'Incredible', 'Fantastic', 'Practical', 'Sleek', 'Awesome', 'Enormous', 'Mediocre', 'Synergistic', 'Heavy Duty', 'Lightweight', 'Aerodynamic', 'Durable'];
        $material = ['Steel', 'Wooden', 'Concrete', 'Plastic', 'Cotton', 'Granite', 'Rubber', 'Leather', 'Silk', 'Wool', 'Linen', 'Marble', 'Iron', 'Bronze', 'Copper', 'Aluminum', 'Paper'];
        $array_product = ['Chair', 'Car', 'Computer', 'Gloves', 'Pants', 'Shirt', 'Table', 'Shoes', 'Hat', 'Plate', 'Knife', 'Bottle', 'Coat', 'Lamp', 'Keyboard', 'Bag', 'Bench', 'Clock', 'Watch', 'Wallet'];

        if (Storage::exists('images/product')) {
            $files = Storage::allFiles("images/product");
            Storage::delete($files);
        }
        $faker = Faker::create();
        for ($i=0; $i < 30; $i++) {
            $pro = $array_product[array_rand($array_product)];
            $product_name = $adjective[array_rand($adjective)] . " " . $material[array_rand($material)] . " " . $pro;
            
            $this->command->line("Try save (" . $i . ")" . $product_name);
            
            $url = "https://source.unsplash.com/random/50x50/?" . $pro;
            $contents = file_get_contents($url);
            $image = str_replace(" ", "_", $product_name) . ".jpg";
            Storage::put("images/product/" . $image, $contents);

            $product = new Product;
            $product->barcode = $faker->isbn10();
            $product->name = $product_name;
            $product->img = $image;
            $product->qty = rand(1, 30);
            $product->price = rand(20, 200) * 1000;
            $product->description = $faker->sentence(6);

            $product->save();
        }
    }
}

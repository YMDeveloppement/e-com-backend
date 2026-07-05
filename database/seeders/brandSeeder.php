<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Brand;

class brandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ["name" => "Apple", "slug" => "apple", "domain" => "apple.com", "logo_url" => "https://logo.clearbit.com/apple.com", "is_active" => 1],
            ["name" => "Samsung", "slug" => "samsung", "domain" => "samsung.com", "logo_url" => "https://logo.clearbit.com/samsung.com", "is_active" => 1],
            ["name" => "Microsoft", "slug" => "microsoft", "domain" => "microsoft.com", "logo_url" => "https://logo.clearbit.com/microsoft.com", "is_active" => 1],
            ["name" => "Google", "slug" => "google", "domain" => "google.com", "logo_url" => "https://logo.clearbit.com/google.com", "is_active" => 1],
            ["name" => "Amazon", "slug" => "amazon", "domain" => "amazon.com", "logo_url" => "https://logo.clearbit.com/amazon.com", "is_active" => 1],
            ["name" => "Meta", "slug" => "meta", "domain" => "meta.com", "logo_url" => "https://logo.clearbit.com/meta.com", "is_active" => 1],
            ["name" => "Netflix", "slug" => "netflix", "domain" => "netflix.com", "logo_url" => "https://logo.clearbit.com/netflix.com", "is_active" => 1],
            ["name" => "Nike", "slug" => "nike", "domain" => "nike.com", "logo_url" => "https://logo.clearbit.com/nike.com", "is_active" => 1],
            ["name" => "Adidas", "slug" => "adidas", "domain" => "adidas.com", "logo_url" => "https://logo.clearbit.com/adidas.com", "is_active" => 1],
            ["name" => "Puma", "slug" => "puma", "domain" => "puma.com", "logo_url" => "https://logo.clearbit.com/puma.com", "is_active" => 1],
            ["name" => "Sony", "slug" => "sony", "domain" => "sony.com", "logo_url" => "https://logo.clearbit.com/sony.com", "is_active" => 1],
            ["name" => "LG", "slug" => "lg", "domain" => "lg.com", "logo_url" => "https://logo.clearbit.com/lg.com", "is_active" => 1],
            ["name" => "Intel", "slug" => "intel", "domain" => "intel.com", "logo_url" => "https://logo.clearbit.com/intel.com", "is_active" => 1],
            ["name" => "AMD", "slug" => "amd", "domain" => "amd.com", "logo_url" => "https://logo.clearbit.com/amd.com", "is_active" => 1],
            ["name" => "Dell", "slug" => "dell", "domain" => "dell.com", "logo_url" => "https://logo.clearbit.com/dell.com", "is_active" => 1],
            ["name" => "HP", "slug" => "hp", "domain" => "hp.com", "logo_url" => "https://logo.clearbit.com/hp.com", "is_active" => 1],
            ["name" => "Lenovo", "slug" => "lenovo", "domain" => "lenovo.com", "logo_url" => "https://logo.clearbit.com/lenovo.com", "is_active" => 1],
            ["name" => "Asus", "slug" => "asus", "domain" => "asus.com", "logo_url" => "https://logo.clearbit.com/asus.com", "is_active" => 1],
            ["name" => "Acer", "slug" => "acer", "domain" => "acer.com", "logo_url" => "https://logo.clearbit.com/acer.com", "is_active" => 1],
            ["name" => "Huawei", "slug" => "huawei", "domain" => "huawei.com", "logo_url" => "https://logo.clearbit.com/huawei.com", "is_active" => 1],
        ];
        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($brand['name'])],
                [
                    'id' => (string) Str::uuid(),
                    'name' => $brand['name'],
                    'logo_url' => 'https://logo.clearbit.com/' . $brand['domain'],
                    'is_active' => 1,
                ]
            );
        }
    }
}

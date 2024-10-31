<?php

namespace Modules\ShopboxPay\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Shopbox\Models\Zpanel\Configuration;

class HNBConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $credentials = $this->getHNBCredentials();

        foreach($credentials as $credential) {
            Configuration::create([ 
                'name' => $credential['name'],
                'value' => $credential['value']
            ]);
        }
    }

    protected function getHNBCredentials()
    {
        return collect([
            ['name' => 'SB_HNB_VERSION', 'value' => '1.0.0'],
            ['name' => 'SB_HNB_MERCHANT_ID', 'value' => '10353900'],
            ['name' => 'SB_HNB_MERCHANT_PASSWORD', 'value' => '6h3GnX6d'],
            ['name' => 'SB_HNB_ACQUIRER_ID', 'value' => '415738'],
            ['name' => 'SB_HNB_RESPONSE_URL', 'value' => ''],
            ['name' => 'SB_HNB_SiGNATURE_METHOD', 'value' => 'sha1'],
            ['name' => 'SB_HNB_CAPTURE_FLAG ', 'value' => 'A'],
        ]);
    }
}

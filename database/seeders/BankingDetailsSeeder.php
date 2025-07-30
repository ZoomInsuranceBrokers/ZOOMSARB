<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankingDetail;

class BankingDetailsSeeder extends Seeder
{
    public function run()
    {
        $banks = [
            [
                'Bank' => 'Kotak Mahindra Bank INR',
                'Account_No' => '0112755638',
                'IFSC_Code' => 'KKBKINBBCPC',
                'Branch' => 'GURGAON - M.G.ROAD, HARYANA',
            ],
            [
                'Bank' => 'Kotak Mahindra Bank USD',
                'Account_No' => '0112755645',
                'IFSC_Code' => 'KKBKINBBCPC',
                'Branch' => 'GURGAON - M.G.ROAD, HARYANA',
            ],
            [
                'Bank' => 'ICICI BANK LTD. INR',
                'Account_No' => '135305002408',
                'IFSC_Code' => 'ICICINBBCTS',
                'Branch' => 'G-20,21, PP TOWER, NETA JI SUBHASH PLACE, PITAMPURA, NEW DELHI-110034',
            ],
            [
                'Bank' => 'ICICI BANK LTD. USD',
                'Account_No' => '135306000058',
                'IFSC_Code' => 'ICICINBBCTS',
                'Branch' => 'G-20,21, PP TOWER, NETA JI SUBHASH PLACE, PITAMPURA, NEW DELHI-110034',
            ]

        ];


        foreach ($banks as $bank) {
            BankingDetail::create($bank);
        }
    }
}

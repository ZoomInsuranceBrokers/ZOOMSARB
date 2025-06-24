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
                'Bank' => 'Kotak Mahindra Bank',
                'Account_No' => '0112755638',
                'IFSC_Code' => 'KKBK0000261',
                'Branch' => 'JMD Regent Square Mehrauli Gurgaon Road Gurgaon-12200',
            ],
            [
                'Bank' => 'HDFC Bank',
                'Account_No' => '1234567890',
                'IFSC_Code' => 'HDFC0001234',
                'Branch' => 'MG Road Branch, Gurgaon',
            ],
            [
                'Bank' => 'State Bank of India',
                'Account_No' => '9876543210',
                'IFSC_Code' => 'SBIN0005678',
                'Branch' => 'Cyber City, Gurgaon',
            ],
        ];

        foreach ($banks as $bank) {
            BankingDetail::create($bank);
        }
    }
}

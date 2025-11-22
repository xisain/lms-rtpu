<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\payment;
use Illuminate\Database\Seeder;

class paymentSeeders extends Seeder
{
    public function run(): void
    {
        $payment_owner = "Muhammad Husain";
        $payment_list = [
            "BCA", "Mandiri", "BRI", "GOPAY"
        ];

        foreach ($payment_list as $payment) {
            // generate nomor rekening acak 10 digit
            $payment_number = rand(1000000000, 9999999999);

            Payment::create([
                'nama' => $payment,
                'account_number' => $payment_number,
                'account_name' => $payment_owner,
            ]);
        }
    }
}

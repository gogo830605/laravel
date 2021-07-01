<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;

class ticketsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ticket::truncate();
        foreach (['A', 'B', 'C'] as $value) {
            $data = new Ticket;
            $data['sort'] = $value;
            $data['name'] = 'ticket'.$value;
            $data['amount'] = rand(2,10);
            $data['start_date'] = date('Y-m-d',strtotime('+'.rand(-3,3).' day'));
            $data['end_date'] = date('Y-m-d',strtotime('+'.rand(1,10).' day', strtotime($data['start_date'])));
            $data->save();
        }
    }
}

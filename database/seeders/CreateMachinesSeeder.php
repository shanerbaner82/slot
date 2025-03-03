<?php

namespace Database\Seeders;

use App\Models\SlotMachine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateMachinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        SlotMachine::create([
            'name' => 'default',
            'configuration' => [
                'apple' => null,
                'apricot' => null,
                'banana' => null,
                'orange' => null,
                'cherry' => null,
                'grapes' => null,
                'lemon' => null,
                'pear' => null,
                'strawberry' => null,
                'watermelon' => null,
                'lucky_seven' => [
                    'odds' => [1, 500],
                    'prize' => 'Hotel Room in LAS VEGAS',
                    'order' => 2
                ],
                'big_win' => [
                    'odds' => [1, 1000],
                    'prize' => 'Seat at the WSOP in LAS VEGAS',
                    'order' => 1
                ]
            ]
        ]);


        SlotMachine::create([
            'name' => 'dragons',
            'configuration' => [
                '1' => null,
                '2' => null,
                '3' => null,
                '4' => null,
                '5' => null,
                '6' => null,
                '7' => null,
                '8' => [
                    'odds' => [1, 500],
                    'prize' => 'Hotel Room in LAS VEGAS',
                    'order' => 2
                ],
                '9' => [
                    'odds' => [1, 1000],
                    'prize' => 'Seat at the WSOP in LAS VEGAS',
                    'order' => 1
                ]
            ]
        ]);
    }
}

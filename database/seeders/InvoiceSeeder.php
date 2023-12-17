<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // User A: Registration and Activations
        Customer::create(['name' => 'Customer A']);
        $userA = User::create(['name' => 'User A', 'email' => 'user1@examle.com', 'password' => bcrypt(123456789), 'registration_date' => '2020-12-01', 'customer_id' => 1]);
        Session::create(['user_id' => $userA->id, 'activated' => '2021-01-15']);
        Session::create(['user_id' => $userA->id, 'activated' => '2021-01-18']);

        // User B: Registration and Appointment
        Customer::create(['name' => 'Customer B']);
        $userB = User::create(['name' => 'User B', 'email' => 'user2@examle.com', 'password' => bcrypt(123456789), 'registration_date' => '2020-12-15', 'customer_id' => 2]);
        Session::create(['user_id' => $userB->id, 'appointment' => '2021-01-15']);

        // User C: Registration and Activations
        Customer::create(['name' => 'Customer C']);
        $userC = User::create(['name' => 'User C', 'registration_date' => '2021-01-01', 'email' => 'user3@examle.com', 'password' => bcrypt(123456789), 'customer_id' => 3]);
        Session::create(['user_id' => $userC->id, 'activated' => '2021-01-10']);

        // User D: Registration, Activations, and Appointments
        Customer::create(['name' => 'Customer D']);
        $userD = User::create(['name' => 'User B', 'registration_date' => '2020-09-01', 'email' => 'user4@examle.com', 'password' => bcrypt(123456789), 'customer_id' => 4]);
        Session::create(['user_id' => $userD->id, 'activated' => '2020-10-11']);
        Session::create(['user_id' => $userD->id, 'activated' => '2021-01-12']);
        Session::create(['user_id' => $userD->id, 'appointment' => '2020-12-27']);
        Session::create(['user_id' => $userD->id, 'appointment' => '2021-01-22']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Importer;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class CustomerController extends BaseController
{
    public function index()
    {
        return DB::table('customers')
                     ->select(DB::raw("CONCAT(first_name, ' ', last_name) AS fullname, email, country"))
                     ->get();;
    }

    public function show($customerId)
    {
        return DB::table('customers')
                     ->select(DB::raw("CONCAT(first_name, ' ', last_name) AS fullname, email,
                                username, gender, country, city, phone"))
                     ->where('id', $customerId)
                     ->get();;
    }

    public function importCustomer()
    {
        $url = 'https://randomuser.me/api/?results=5000';
        $nat = 'au';
        $minimumUser = 100;

        return Importer::import($url, $minimumUser, $nat);
    }
}

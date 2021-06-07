<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Importer extends Model
{
    public static function import($url, $minimumUser, $nat)
    {
        $ch = curl_init();
        
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json;charset=UTF-8"));

            $result = curl_exec($ch);
            
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }

        curl_close ($ch);

        $response = json_decode($result, TRUE);

        $data = array();
        if(isset($response['results'])) {
            Schema::create('temp_table', function (Blueprint $table) {
                $table->string('first_name');
                $table->string('last_name');
                $table->string('username');
                $table->string('email');
                $table->string('gender');
                $table->string('country');
                $table->string('city');
                $table->string('phone');
                $table->string('password');
                $table->string('nat');
                $table->timestamps();
                $table->temporary();
            });
        
            foreach($response['results'] as $key => $res){
                DB::table('temp_table')->insert(
                            ['first_name'=> $res['name']['first'],
                            'last_name'=> $res['name']['last'],
                            'username'=> $res['login']['username'],
                            'email'=> $res['email'],
                            'gender'=> $res['gender'],
                            'country'=> $res['location']['country'],
                            'city'=> $res['location']['city'],
                            'phone'=> $res['phone'],
                            'nat'=> $res['nat'],
                            'password'=> $res['login']['password']]);
            }
        
            $data = DB::table('temp_table')->where('nat', $nat)->get();
        
            Schema::drop('temp_table');
        }

        if(count($data) >= $minimumUser)  { //minimum of 100 users can store
            try {
                foreach($data as $x){
                    Customer::updateOrCreate([
                        'email' => $x->email
                    ],[
                        'first_name' => $x->first_name, 
                        'last_name' => $x->last_name, 
                        'username' => $x->username, 
                        'email' => $x->email, 
                        'gender' => $x->gender, 
                        'country' => $x->country, 
                        'city' => $x->city, 
                        'phone' => $x->phone, 
                        'password' => md5($x->password), 
                    ]);
                }
                return response()->json('Users successfully imported', 200);
            }  catch (Exception $e) {
                return response()->json($e->getMessage(), 500);
            }
        } else {
            return response()->json('This service can only accept minimum of 100 users.', 400);
        }
    }
}
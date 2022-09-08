<?php

namespace App\Http\Controllers\Api;

use App\Console\Commands\InsertUserCommand;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use DB;

class InsertUserController extends Controller
{


    public $page = 1;

    public function getUsers()
    {

        try {

            $response = Http::get('https://reqres.in/api/users?page=' . $this->page);

            $actual_page = $response['page'];
            $total_pages = $response['total_pages'];
            $data = $response['data'];

            $this->insertUser($data);

            if ($actual_page < $total_pages) {
                $this->page = $this->page + 1;
                $this->getUsers();
            }
        } catch (\Exception $e) {
            //
        }
    }


    public function insertUser($datausers)
    {

        DB::beginTransaction();
        try {

            foreach ($datausers as $key => $user) {


                $validate_user = User::where('email', $user['email'])->select("id")->first();
                if (isset($validate_user)) {
                    //YA EXISTE
                    $insertUser = User::find($validate_user->id);
                    $insertUser->first_name = $user['first_name'];
                    $insertUser->last_name = $user['last_name'];
                    $insertUser->email = $user['email'];
                    $insertUser->avatar = $user['avatar'];
                    $insertUser->save();
                } else {
                    //NUEVO USUSARIO
                    $insertUser = new User();
                    $insertUser->first_name = $user['first_name'];
                    $insertUser->last_name = $user['last_name'];
                    $insertUser->email = $user['email'];
                    $insertUser->avatar = $user['avatar'];
                    $insertUser->email_verified_at = now();
                    $insertUser->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password
                    $insertUser->remember_token = Str::random(10);
                    $insertUser->save();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }
}

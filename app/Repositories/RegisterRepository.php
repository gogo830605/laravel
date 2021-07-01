<?php
namespace App\Repositories;

use App\Models\user;
use Str;

class RegisterRepository
{
    private $model;
    public $table_name;
    public function __construct()
    {
        $this->model = new user;
        $this->table_name = $this->model->getTable();
    }
    public function saveRegister($arr)
    {
        $data = new $this->model;
        $data['name'] = $arr['name'];
        $data['email'] = $arr['email'];
        $data['password'] = bcrypt($arr['password']);
        // $data['email_verified_at'] = Str::random(10);

        // update table event
        $status = $data->save();
        return ($status)?$data:false;
    }
}

<?php
namespace App\Http\Repository;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Exception;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected User $model
    ){}
    
    public function find($id)
    {
        $data = $this->model->where('user_login_id', $id)->get()->first();

        return $data;
    }

    public function create($user_DATA, $login_ID, $addres_ID)
    {
        $record = $this->model->create([
            'name'                => $user_DATA->name,
            'email'               => $user_DATA->email,
            'phone'               => $user_DATA->phone,
            'gender'              => $user_DATA->gender,
            'born_at'             => $user_DATA->born_at,
            'document'            => $user_DATA->document,
            'user_login_id'       => $login_ID,
            'common_user_address' => $addres_ID,
        ]);
        
        return $record;
    }

    public function update($user_DATA, $login_ID, $addres_ID)
    {
        // error_log($user_DATA);

        $record = $this->model->where('document', $user_DATA->document)->get()->first();

        $record->name                = $user_DATA->name;
        $record->email               = $user_DATA->email;
        $record->phone               = $user_DATA->phone;
        $record->gender              = $user_DATA->gender;
        $record->born_at             = $user_DATA->born_at;
        $record->document            = $user_DATA->document;
        $record->user_login_id       = $login_ID;
        $record->common_user_address = $addres_ID;

        $record->save();

        // $id = $this->model->where('document', $user_DATA->document)->get()->first();
        
        return $record;
    }

    public function search($search, $argument)
    {
        $data = $this->model->where($search, $argument)->get();

        return $data;
    }
}
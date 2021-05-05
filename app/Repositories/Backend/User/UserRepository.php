<?php


namespace App\Repositories\Backend\User;

use App\Events\KeywordEvents;
use App\Models\User;

class UserRepository
{
    public function getAllUsers($paginate = 10)
    {
        $result = User::all(); //paginate($paginate);
        return $result;
    }    
}

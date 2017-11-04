<?php
namespace App\Entities\Users;

use App\Entities\BaseRepository;
use App\Exceptions\UpdateUserException;
use App\Services\Facades\Option;

/**
 * Users Repository Class
 */
class UsersRepository extends BaseRepository
{

    protected $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getUsers($q)
    {
        return User::where('name', 'like', '%'.$q.'%')
            ->paginate($this->_paginate);
    }

    public function create($userData)
    {
        if ($userData['password'] == '') {
            $userData['password'] = Option::get('password_default', 'member');
        }

        $user = $this->storeArray($userData);

        return $user;
    }

    public function update($userData, $userId)
    {
        $user = $this->requireById($userId);

        foreach ($userData as $key => $value) {
            $user->{$key} = $value;
        }

        if ($user->save()) {
            return $user;
        }

        throw new UpdateUserException('Failed to update User');
    }

    public function delete($userId)
    {
        $user = $this->requireById($userId);

        return $user->delete();
    }
}

<?php

namespace Api\Controllers;

use Api\Models\User;
use Core\Controller;
use Exception;

/**
 * User controller
 */
class UserController extends Controller
{

    /**
     * @return void
     * @throws Exception
     */
    public function indexAction() {
        $users = User::getAll();
        if ($users)
            self::response($users);

        self::error('Users not found.', 404);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function createAction() {
        $user_data = (object)$this->route_params['body'];
        if (isset($user_data->username)) {
            if (!empty($user_data->username)) {

                if (User::hasUniqueUsername($user_data->username))
                    self::error('username duplicated.', 409);

                if (isset($user_data->email) && !empty($user_data->email) && User::hasUniqueEmail($user_data->email))
                    self::error('email duplicated.', 409);

                if (User::create($user_data))
                    self::response(['success' => 'User create successful.']);
                else
                    self::error('Error on create user.');
            } else
                self::error('username could not empty.', 204);
        } else
            self::error('username not send.', 204);
    }
}

<?php
namespace src\Handlers;

use \src\models\User;

class LoginHandler {

    public static function checkLogin() {
        if(!empty($_SESSION['token'])) {
            $token = $_SESSION['token'];

            $data = User::select()->where('token', $token)->one();
            if(count($data) > 0) {

                $loggedUser = new User;
                $loggedUser->setId($data['id']);
                $loggedUser->setEmail($data['email']);
                $loggedUser->setName($data['name']);

                return $loggedUser;

            }

        }
        return false; //como de qler forma se nao retornar o loggeuser vai dar return false, basta resumir o else
    } 
 

    public static function verifyLogin($email, $password) {
        $user = User::select()->where('email', $email)->one();

        if($user) {
            if(password_verify($password, $user['password'])) {
                $token = md5(time().rand(0,9999).time());

                User::update()
                    ->set('token', $token)
                    ->where('email', $email)
                ->execute();

                return $token;
            }
        }

        return false;
    }


}
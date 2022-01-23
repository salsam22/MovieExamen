<?php

namespace App\Controller;

use App\FlashMessage;
use App\Mapper\UserMapper;
use App\Registry;
use App\Repository\UserRepository;
use App\Response;
use App\User;

class UserController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $mapper = new UserMapper();
        $this->userRepository = new UserRepository($mapper);
    }

    public function register(): Response
    {

        $errors = [];

        if (empty($data)) {
            $data["username"] = "";
            $data["password"] = "";
        }

        if (isPost()) {
            if (empty($_POST["username"])) {
                $errors[] = "Username obligatori";
            } else {
                $data["username"] = $_POST["username"];
            }
            if (empty($_POST["password"])) {
                $errors[] = "Password obligatori";
            } else if ($_POST["password"] != $_POST["passwordc"]) {
                $errors[] = "Password copy no igual";
            } else {
                $data["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);
            }

            if (empty($errors)) {
                try {
                    $user = User::fromArray($data);
                    $this->userRepository->save($user);
                    FlashMessage::set("info", "T'has registrar correctament.");
                    header("Location: /");
                    exit();
                } catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                }

            }
        }

        $response = new Response();
        $response->setView("register");
        $response->setLayout("default");
        $response->setData(compact('errors', 'data'));

        return $response;
    }

    public function login() {
        $errors = [];

        if (empty($data)) {
            $data["username"] = "";
            $data["password"] = "";
        }

        if (isPost()) {
            if (empty($_POST["username"])) {
                $errors[] = "Username obligatori";
            } else {
                $data["username"] = $_POST["username"];
            }
            if (empty($_POST["password"])) {
                $errors[] = "Password obligatori";
            }

            if (empty($errors)) {
                try {
                    $user = User::fromArray($data);
                    $password = $this->userRepository->login($user);
                } catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                }
            }

        }
        $response = new Response();
        $response->setView("register");
        $response->setLayout("default");
        $response->setData(compact(''));

        return $response;

    }

    public function loggout(){
        $errors = [];
        $message = "S'ha tancat la sessio correctament.";
        if(!empty($_COOKIE["last_used_name"])){
            setcookie("last_used_name", "", -1);
        }
        session_unset();
        session_destroy();

        header("Location: ".Registry::get(Registry::ROUTER)->generate("movie_list"));

        $response = new Response();
        $response->setView("loggout");
        $response->setLayout("default");
        $response->setData(compact(  'errors', 'message'));
        return $response;
    }
}
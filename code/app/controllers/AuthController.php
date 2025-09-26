<?php

namespace App\controllers;

use Config\Config;
use League\Plates\Engine;
use function Tamtamchik\SimpleFlash\flash;
use Delight\Auth\Auth;

require_once(__DIR__ . '/../../config/GlobalsConfig.php');

class AuthController
{
    private $db = null;
    private $auth = null;
    private $template = null;
    private $anException = true;

    public function __construct(Engine $engine, Auth $auth)
    {
        $this->template = $engine;
        $this->auth = $auth;
    }

    public function showPageRegistration()
    {
        echo $this->template->render("registration", ["anException" => $this->getExceptionFlag()]);
    }

    public function registration()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = trim(filter_var($_POST["email"], FILTER_SANITIZE_EMAIL)) ?? "";
            $password = trim($_POST["password"]) ?? "";
            $username = trim(htmlspecialchars($_POST["username"])) ?? "";
        }

        try {
            $userId = $this->auth->register($email, $password, $username, function ($selector, $token) {
                Config::setDataConfig("verificationData.selector", $selector);
                Config::setDataConfig("verificationData.token", $token);
            
                flash()->info("Verification user after registration - <a href='https://composer-practics.ru/verification?selector=".urlencode($selector)."&token=".urlencode($token)."'>Click here</a>");
            });

            $this->setExceptionFlag(false);
            flash()->success("We have signed up a new user with the ID $userId");
        } catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error("'Invalid email address'");
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error("Invalid password");
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error("User already exists");
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error("Too many requests");
        }

        echo $this->template->render("registration", ["anException" => $this->getExceptionFlag()]);
    }

    public function emailVerification()
    {
        $selector = $_GET["selector"] ?? "";
        $token = $_GET["token"] ?? "";
        d($_GET);
        try {
            $this->auth->confirmEmail($selector, $token);

            $this->setExceptionFlag(false);
            flash()->success("Email address has been verified");
        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            die('Invalid token');
        } catch (\Delight\Auth\TokenExpiredException $e) {
            die('Token expired');
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('Email address already exists');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }

        echo $this->template->render("verification", ["anException" => $this->getExceptionFlag()]);
    }

    public function showPageLogin()
    {
        echo $this->template->render("auth", ["anException" => $this->getExceptionFlag()]);
    }

    public function login()
    {
        $this->template->addData(["isLoggedUser" => $this->auth->isLoggedIn()], "layout");
        try {
            $this->auth->login($_POST['email'], $_POST['password']);

            header("Location: /home");
            flash()->success("User is logged in");
            $this->setExceptionFlag(false);
        } catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error("Wrong email address");
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error("Wrong password");
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            flash()->error("Email not verified");
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error("Too many requests");
        }

        echo $this->template->render("auth", ["anException" => $this->getExceptionFlag()]);

    }

    public function logout()
    {
        $this->auth->logOut();

        header("Location: /auth");
    }

    public function getExceptionFlag()
    {
        return $this->anException;
    }

    public function setExceptionFlag($flag)
    {
        $this->anException = $flag;
    }
}

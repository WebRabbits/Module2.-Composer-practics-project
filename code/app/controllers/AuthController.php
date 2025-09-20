<?php

namespace App\controllers;

use App\QueryBuilder;
use Connection\Connection;
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

    public function __construct()
    {
        $this->db = Connection::Connect();
        $this->auth = new Auth($this->db, null, null, false);
        $this->template = new Engine("../app/views");
    }

    public function showPageRegistration()
    {
        echo $this->template->render("registration", ["anException" => $this->getExceptionFlag()]);
    }

    public function registration($vars)
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = trim(filter_var($_POST["email"], FILTER_SANITIZE_EMAIL)) ?? "";
            $password = trim($_POST["password"]) ?? "";
            $username = trim(htmlspecialchars($_POST["username"])) ?? "";
        }

        try {
            $userId = $this->auth->register($email, $password, $username, function ($selector, $token) {
                flash()->info("'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)<br>For emails, consider using the mail(...) function, Symfony Mailer, Swiftmailer, PHPMailer, etc.<br>For SMS, consider using a third-party service and a compatible SDK");
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
        try {
            $this->auth->confirmEmail(Config::getDataConfig("verificationData.selector"), Config::getDataConfig("verificationData.token"));

            echo 'Email address has been verified';
        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            die('Invalid token');
        } catch (\Delight\Auth\TokenExpiredException $e) {
            die('Token expired');
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('Email address already exists');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
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

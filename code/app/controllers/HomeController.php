<?php

namespace App\controllers;

use App\exceptions\AccountIsBlockedException;
use App\exceptions\NotEnoughtMoneyException;
use Connection\Connection;
use League\Plates\Engine;
use function Tamtamchik\SimpleFlash\flash;
use Delight\Auth\Auth;
use Delight\Auth\Role;

require_once(__DIR__ . "/../../config/GlobalsConfig.php");

class HomeController
{
    private $db = null;
    private $auth = null;
    private $template = null;

    public function __construct()
    {
        $this->db = Connection::Connect();
        $this->auth = new Auth($this->db, null, null, false);
        $this->template = new Engine("../app/views");
    }
    public function index($vars)
    {
        $this->template->addData(["isLoggedIn" => $this->auth->isLoggedIn()], "homepage");
        if ($this->auth->isLoggedIn()) {
            echo $this->template->render("homepage", $this->renderDataHomepage());

            // $this->auth->admin()->addRoleForUserById(11, Role::COLLABORATOR);
            // $this->auth->admin()->removeRoleForUserById(11, Role::ADMIN);
            // d($this->auth->getRoles());

        } else {
            echo $this->template->render("homepage");
            flash()->error("User is not signed in yet");
        }
    }

    public function about($vars)
    {
        try {
            $this->withdraw($vars["amount"]);
        } catch (NotEnoughtMoneyException $e) {
            flash()->error($e->getMessage() . " code: " . $e->getCode());
        } catch (AccountIsBlockedException $e) {
            flash()->error($e->getMessage());
        }

        echo $this->template->render("about", ["page" => "ABOUT", "name" => "Roman"]);
    }

    public function withdraw($amount = 1)
    {
        static $total = 10;

        if (true) {
            // throw new AccountIsBlockedException("Ваш аккаунт заблокирован!");
        }

        if ($amount > $total) {
            // ... Exception
            throw new NotEnoughtMoneyException("Недостаточно средств", 111);
        }

        $total -= $amount;

        flash()->success("Вы вывели сумму $amount EUR. Доступно для списания $total");
        return;
    }

    public function changePassword()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $oldPassword = trim($_POST["oldPassword"]);
            $newPassword = trim($_POST["newPassword"]);
        }

        try {
            $this->auth->changePassword($oldPassword, $newPassword);

            $this->auth->logOut();
            flash()->info("Password has been changed");
            header("Location: /auth");
        } catch (\Delight\Auth\NotLoggedInException $e) {
            flash()->error("Not logged in");
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error("Invalid password(s)");
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error("Too many requests");
        }

        echo $this->template->render("homepage", $this->renderDataHomepage());
    }

    public function renderDataHomepage()
    {
        return [
            "name" => $this->auth->getUsername(),
            "email" => $this->auth->getEmail(),
            "isLoggedIn" => $this->auth->isLoggedIn()
        ];
    }
}

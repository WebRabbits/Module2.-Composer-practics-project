<?php

namespace App\controllers;

use App\exceptions\AccountIsBlockedException;
use App\exceptions\NotEnoughtMoneyException;
use App\QueryBuilder;
use League\Plates\Engine;
use Exception;
use function Tamtamchik\SimpleFlash\flash;

class HomeController
{
    private $templates = null;

    public function __construct()
    {
        $this->templates = new Engine("../app/views");
    }
    public function index($vars)
    {
        echo $this->templates->render("homepage", ["name" => "Belfort"]);
    }

    public function about($vars)
    {
        try {
            $this->withdraw($vars["amount"]);
        } catch (NotEnoughtMoneyException $e) {
            flash()->error($e->getMessage() . " code: " . $e->getCode());
        } catch(AccountIsBlockedException $e){
            flash()->error($e->getMessage());

        }

        echo $this->templates->render("about", ["page" => "ABOUT", "name" => "Roman"]);
    }

    public function withdraw($amount = 1)
    {
        static $total = 10;

        if(true){
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
}

<?php

namespace App;

use Aura\SqlQuery\QueryFactory;
use PDO;

class QueryBuilder
{

    private $pdo;
    private $queryFactory;
    private $lastInsertId = null;
    private $result;

    public function __construct(PDO $pdo, QueryFactory $queryFactory)
    {
        $this->pdo = $pdo;
        $this->queryFactory = $queryFactory;
    }

    public function getAll($table)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(["*"])->from($table);
        $stmt = $this->pdo->prepare($select->getStatement());
        $stmt->execute();

        $this->result = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $this;
    }

    // Получить данные из БД по переданному условию
    public function get($table, $operator, $where = [])
    {
        $select = $this->queryFactory->newSelect();
        $operators = ["=", ">", "<", ">=", "<=", "!=", "LIKE"];
        $column = key($where);

        if (isset($where)) {
            if (in_array($operator, $operators, true)) {
                $select->cols(["*"])->from($table)->where("$column $operator :$column", $where);
                $stmt = $this->pdo->prepare($select->getStatement());

                $stmt->execute($select->getBindValues());

                $this->result = $stmt->fetchAll(PDO::FETCH_OBJ);
                return $this;
            }
        }
    }

    public function insert($table, $data)
    {
        $insert = $this->queryFactory->newInsert();

        $insert->into($table)->cols($data);

        $this->prepareAndExecute($insert);

        $id = $insert->getLastInsertIdName("id");
        $this->lastInsertId = $this->pdo->lastInsertId($id);
    }

    //Сделать проверку получения записи из БД на её наличие - и если НЕТ, то вернуть ошибку
    public function update($table, $data, $id)
    {
        $update = $this->queryFactory->newUpdate();

        $update->table($table)->cols($data)->where("id = :id", ["id" => $id]);

        $this->prepareAndExecute($update);
    }

    public function delete($table, $id)
    {
        $delete = $this->queryFactory->newDelete();

        $delete->from($table)->where("id = :id")->bindValue("id", $id);

        $this->prepareAndExecute($delete);
    }

    // Обёртка над операцией подготовки данных для передачи в запрос и выполнением самого SQL-запроса
    public function prepareAndExecute($operation)
    {
        $stmt = $this->pdo->prepare($operation->getStatement());
        $stmt->execute($operation->getBindValues());
        return;
    }

    // Получение ID последней добавленной записи в БД
    public function getLastInsertId()
    {
        return $this->lastInsertId;
    }

    // Получить полный результат запроса в БД (SELECT)
    public function result()
    {
        return $this->result;
    }

    // Получить первую запись из всего массива данных полученных по запросу из БД (SELECT)
    public function findOne()
    {
        if (!empty($this->result())) {
            return $this->result()[0];
        }

        return false;
    }

    // Метод позволяет получить определённый массив данных из БД. Таблицы передаётся через параметре $table. Лимит получаемых данных передаётся через параметр $limit (кол-во получаемых данных в результате ответа). Отступ/шаг выборки (offset) задаётся в через параметр $page, который принимает значение из GET параметра переданного в запросе.
    public function limitOffsetPaginationData($table, int $limit, string $aliasPage) {
        $select = $this->queryFactory->newSelect();
        $select->cols(["*"])->from($table)->setPaging($limit)->page($_GET[$aliasPage] ?? 1);
        $stmt = $this->pdo->prepare($select->getStatement());
        $stmt->execute();

        $this->result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $this;
    }
}

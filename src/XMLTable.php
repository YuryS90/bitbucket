<?php

namespace App;

use App\XML;

class XMLTable extends XML
{
    protected $errors = [];

    // Метод, котоый будет добвлять но с проверкой. Мы его просто перезагружаем.  Для того чтобы добавить проверку
    public function addData($login, $pass, $email, $name)
    {
        if (!$this->existenceValue('login', $login)) {
            if (!$this->existenceValue('email', $email)) {
                return parent::addData($login, $pass, $email, $name); // если не дойдём до неё, то вернём false
            } else {
                $this->errors[] = "Такой email существует";
            }
        } else {
            $this->errors[] = "Такой логин существует";
        }
        return false;
    }

    // возвращает ошибки (тому кто спросит) и потом их стирает
    public function getErrors()
    {
        $errors = $this->errors;
        $this->errors = [];
        return $errors;
    }
}

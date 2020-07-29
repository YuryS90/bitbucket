<?php

namespace App;

class XML
{
    //1. Прочитать данные из файла, чтобы комп знал с чем работает
    //2. добавить данные про пользователя
    //3. сохранить данные в файл

    // protected $fileName = "bd.xml";
    protected $data;

    public function readFile($fileName)
    {
        preg_match_all(
            // "/<(.{1,})>(.*?)<\/.{1,}><(.{1,})>(.*?)<\/.{1,}><(.{1,})>(.*?)<\/.{1,}><(.{1,})>(.*?)<\/.{1,}>/ui",
            "/<login>(.*?)<\/login><password>(.*?)<\/password><email>(.*?)<\/email><name>(.*?)<\/name>/ui",
            file_get_contents($fileName), // читаем из файла .xml строку
            $arr_tags
        ); // вытаскиваем данные из xml-тегов
        // print_r($arr_tags);
        $this->data = [];

        foreach ($arr_tags[1] as $key => $value) {  // формируем
            $this->data[$key]['login'] = $value;
            $this->data[$key]['password'] = $arr_tags[2][$key];
            $this->data[$key]['email'] = $arr_tags[3][$key];
            $this->data[$key]['name'] = $arr_tags[4][$key];
        }
        // print_r($this->data);
        return $this;
    }

    // делаем метод чтобы возврашщал data
    public function getData(): array
    {
        return $this->data;
    }



    public function addData($login, $pass, $email, $name)
    {
        //чтобы посмотреть как выглядит массив (ключ-значение), и узнать каким образом добавить
        // print_r($this->data);

        // if (empty($login)) {
        //     echo "Заполните поле Логин";

        // }

        // if (empty($pass)) {
        //     echo "Заполните поле Пароль";

        // }

        // if (empty($email)) {
        //     echo "Заполните поле Email";

        // }

        // if (empty($name)) {
        //     echo "Заполните поле Имя";

        // }

        // if ($this->uniqueField('login', $login)) {
        //     echo "Такой логин существуют";
        //     if ($this->uniqueField('email', $email)) {
        //         echo "Такой email существуют";
        //     } else {
        //         $this->data[] = [
        //             'login' => $login,
        //             'password' => $pass,
        //             'email' => $email,
        //             'name' => $name
        //         ];
        //     }
        // }

        // if($this->uniqueField('email', $email)) {
        //     echo "Такой email существуют";
        // }
        $this->data[] = ['login' => $login, 'password' => $pass, 'email' => $email, 'name' => $name];


        return $this;
    }


    // преобразуем массив в Xml строку 
    public function saveFile($fileName)
    {
        if (!empty($this->data)) {
            $xml = "<?xml version=\"1.1\" encoding=\"UTF-8\"?>\n";
            foreach ($this->data as $row) {
                $xml .= "<row>";
                foreach ($row as $key => $value) {
                    $xml .= "<$key>$value</$key>";
                }
                $xml .= "</row>\n";
            }
            // echo $xml;
            file_put_contents($fileName, $xml);
        }
        return $this;
    }

    // Метод который будет добавлять но с проверкой
    // МЕТОД КОТОРЫЙ ПРОВЕРЯЕТ ПЕРЕД СОХРАНЕНИЕМ УНИКАЛЬНОСТЬ И


    // есть ли такое значение  value в столбце field
    protected function existenceValue($field, $value): bool
    {
        return in_array($value, array_column($this->data, $field));
    }

    // public function checkPass($password, $confirmPassword)
    // {
    //     if($password != $confirmPassword) {
    //         echo "Потвердите пароль ещё раз";
    //     }
    //     return $this;
    // }
}

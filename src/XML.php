<?php

namespace App;

class XML
{
    //1. Прочитать данные из файла Для чего?
    //2. добавить данные про пользователя
    //3. сохранить данные в файл ДЛя чего???

    protected $fileName = "bd.xml";
    protected $data;

    public function readFile()
    {
        preg_match_all(
            // "/<(.{1,})>(.*?)<\/.{1,}><(.{1,})>(.*?)<\/.{1,}><(.{1,})>(.*?)<\/.{1,}><(.{1,})>(.*?)<\/.{1,}>/ui",
            "/<login>(.*?)<\/login><password>(.*?)<\/password><email>(.*?)<\/email><name>(.*?)<\/name>/ui",
            file_get_contents($this->fileName), // читаем из файла .xml строку
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



    public function addData($login, $pass, $email, $name)
    {
        //чтобы посмотреть куда добавляю
        print_r($this->data);
        $this->data[] = ['login' => $login, 'password' => $pass, 'email' => $email, 'name' => $name];
        return $this;
    }


    // преобразуем массив в Xml строку 
    public function saveFile()
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
            file_put_contents($this->fileName, $xml);
        }
        return $this;
    }
}

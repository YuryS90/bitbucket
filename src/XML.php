<?php

namespace App;

class XML
{
    protected $fileName = "bd.xml";

    public function readFile()
    {
        preg_match_all(
            // "/<(.{1,})>(.*?)<\/.{1,}><(.{1,})>(.*?)<\/.{1,}><(.{1,})>(.*?)<\/.{1,}><(.{1,})>(.*?)<\/.{1,}>/ui",
            "/<login>(.*?)<\/login><password>(.*?)<\/password><email>(.*?)<\/email><name>(.*?)<\/name>/ui",
            file_get_contents($this->fileName), // читаем из файла .xml строку
            $arr_tags
        ); // вытаскиваем данные из xml-тегов
        print_r($arr_tags);
        $arr_guests_book = [];

        foreach ($arr_tags[1] as $key => $value) {  // формируем
            $arr_guests_book[$key]['login'] = $value;
            $arr_guests_book[$key]['password'] = $arr_tags[2][$key];
            $arr_guests_book[$key]['email'] = $arr_tags[3][$key];
            $arr_guests_book[$key]['name'] = $arr_tags[4][$key];
        }
        // print_r($arr_guests_book);
        return $arr_guests_book;
    }

}

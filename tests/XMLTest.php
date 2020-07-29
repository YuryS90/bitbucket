<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\XML;
use App\XMLTable;

class XMLTest extends TestCase
{
    public function testXML()
    {
        $xml = new XML();


        // ТЕСТ ЧТО УМЕЕТ СЧИТЫВАТЬ С ФАЙЛА
        //getData() - возвращает массив
        $this->assertSame(
            [
                0 =>
                [
                    'login' => 'Petr15',
                    'password' => '12',
                    'email' => 'Petr@mail.ru',
                    'name' => 'Пётр Иванов'
                ]
            ],
            $xml
                ->readFile('tests/test.xml')
                ->getData()
        );

        //ТЕСТ ЧТО МОЖЕТ ЧТО-ТО ДОБАВИТЬ
        //getData() - КАКИЕ ДАННЫЕ ПОЛУЧАЕЮТСЯ 
        $this->assertSame(
            [
                0 =>
                [
                    'login' => 'Petr15',
                    'password' => '12',
                    'email' => 'Petr@mail.ru',
                    'name' => 'Пётр Иванов'
                ],
                1 =>
                [
                    'login' => 'Petr151',
                    'password' => '12',
                    'email' => 's@mail.ru',
                    'name' => 'Иванов'
                ]
            ],
            $xml
                ->readFile('tests/test.xml')
                ->addData('Petr151', '12', 's@mail.ru', 'Иванов')
                ->getData()
        );


        //ТЕСТ ЧТО УМЕЕТ СОХРАНЯТЬ
        $this->assertSame(
            [
                0 =>
                [
                    'login' => 'Petr15',
                    'password' => '12',
                    'email' => 'Petr@mail.ru',
                    'name' => 'Пётр Иванов'
                ],
                1 =>
                [
                    'login' => 'Petr151',
                    'password' => '12',
                    'email' => 's@mail.ru',
                    'name' => 'Иванов'
                ]
            ],
            $xml
                ->readFile('tests/test.xml')
                ->addData('Petr151', '12', 's@mail.ru', 'Иванов')
                ->saveFile('tests/test2.xml')
                ->readFile('tests/test2.xml')
                ->getData()
        );

        // Проверка на существование значения
        // $this->assertSame(
        //     true,
        //     $xml
        //         ->readFile('tests/test2.xml')
        //         ->existenceValue('login', 'Petr151')
        // );
    }

    public function testXMLTable()
    {
        $xml = new XMLTable();

        $xml
            ->readFile('tests/test.xml')
            ->addData('Petr15', '123', 'Petr123@mail.ru', 'Пётр1 Иванов'); // пытаюсь добавить сново

        // проверка, если такой логин существует то не добавляем
        $this->assertSame(
            [
                0 =>
                [
                    'login' => 'Petr15',
                    'password' => '12',
                    'email' => 'Petr@mail.ru',
                    'name' => 'Пётр Иванов'
                ]
            ],
            $xml->getData()
        );

        $this->assertSame(
            [0 => "Такой логин существует"],
            $xml->getErrors()
        );

        // проверка, если такой емэйл существует то не добавляем
        $xml
            ->readFile('tests/test.xml')
            ->addData('Pet', '1', 'Petr@mail.ru', 'Петька'); // ВОЗВРАЩЕТ ФАЛЬШ, А НЕ ОБЪЕКТ

        $this->assertSame(
            [
                0 =>
                [
                    'login' => 'Petr15',
                    'password' => '12',
                    'email' => 'Petr@mail.ru',
                    'name' => 'Пётр Иванов'
                ]
            ],
            $xml->getData()
        );

        $this->assertSame(
            [0 => "Такой email существует"],
            $xml->getErrors()
        );
    }
}

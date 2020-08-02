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

        /**
         * проверка поля ЛОГИНА на пустоту
         */
        $this->assertFalse(
            $xml
                ->readFile('tests/test.xml')
                ->addData('', '123', 'Petr123@mail.ru', 'Пётр1 Иванов')
        );

        $this->assertSame(
            [0 => "Поле с логином не должно быть пустым!"],
            $xml->getErrors()
        );


        /**
         * проверка поля ПАРОЛЯ на пустоту
         * 
         * +
         *
         * тест на то, что действительно ли вернёт логическое значение, т.к  
         * при пустом значении ('Petr', '1', '', 'Пётр1 Иванов')
         * должно вернуть false
         */
        $this->assertFalse(
            $xml
                ->readFile('tests/test.xml')
                ->addData('Petr', '', 'Petr123@mail.ru', 'Пётр1 Иванов')
        );

        $this->assertSame(
            [0 => "Поле с паролем не должно быть пустым!"],
            $xml->getErrors()
        );

        /**
         * тест на то, что действительно ли вернёт объект, т.к  
         * при НОВЫХ значениях ('Igor', '11', 'igor@mail.ru', 'Игорь Иванов')
         * должно вернуть $this
         */
        $this->assertIsObject(
            $xml
                ->readFile('tests/test.xml')
                ->addData('Igor', '11', 'igor@mail.ru', 'Игорь Иванов')
        );

        /**
         * проверка поля EMAIL на пустоту
         */
        $this->assertFalse(
            $xml
                ->readFile('tests/test.xml')
                ->addData('Petr', '1', '', 'Пётр1 Иванов')
        );

        $this->assertSame(
            [0 => "Поле с email не должно быть пустым!"],
            $xml->getErrors()
        );


        /**
         * проверка поля ИМЯ на пустоту
         */
        $this->assertFalse(
            $xml
                ->readFile('tests/test.xml')
                ->addData('Petr', '1', 'Petr123@mail.ru', '')
        );

        $this->assertSame(
            [0 => "Поле с именем не должно быть пустым!"],
            $xml->getErrors()
        );


        // проверка, если такой логин существует, то не добавляем
        $xml
            ->readFile('tests/test.xml')
            ->addData('Petr15', '123', 'Petr123@mail.ru', 'Пётр1 Иванов'); // пытаюсь добавить сново


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

        // проверка, если такой email существует, то не добавляем
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



        /**
         * авторизация: соответствие логина и пароля
         */
        $this->assertTrue(
            $xml
                ->readFile('tests/test21.xml')
                ->checkUser('Petr151', 'ce0f7dc57c388faf9418513c64148b5b')
        );


        /**
         * авторизация: НЕ соответствие логина
         */
        $this->assertFalse(
            $xml
                ->readFile('tests/test3.xml')
                ->checkUser('Igor1', '15')
        );


        /**
         * авторизация: НЕ соответствие пароля
         */
        $this->assertFalse(
            $xml
                ->readFile('tests/test3.xml')
                ->checkUser('Igor', '151')
        );
    }
}

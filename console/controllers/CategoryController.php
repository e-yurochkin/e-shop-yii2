<?php

namespace console\controllers;

use backend\models\Category;
use Yii;
use yii\console\Controller;
use yii\db\Exception;

class CategoryController extends Controller
{
    private function getCategories(): array
    {
        return [
            [// id=1
                'name' => 'Base category',
                'parent_id' => null,
            ],
            [// id=2
                'name' => 'Телефоны и связь',
                'parent_id' => 1,
            ],
            [// id=3
                'name' => 'Компьютерная техника',
                'parent_id' => 1,
            ],
            [// id=4
                'name' => 'TV и видео',
                'parent_id' => 1,
            ],

            // Телефоны и связь (id=2)
            [// id=5
                'name' => 'Мобильные телефоны и планшеты',
                'parent_id' => 2,
            ],
            [// id=6
                'name' => 'Связь и IP-телефония',
                'parent_id' => 2,
            ],
            [// id=7
                'name' => 'Защитные аксессуары и крепления',
                'parent_id' => 2,
            ],
            // Телефоны и связь (id=2)

            // Компьютерная техника (id=3)
            [// id=8
                'name' => 'Ноутбуки и аксессуары',
                'parent_id' => 3,
            ],
            [// id=9
                'name' => 'Компьютеры и ПО',
                'parent_id' => 3,
            ],
            [// id=10
                'name' => 'Периферия для ПК и аксессуары',
                'parent_id' => 3,
            ],
            // Компьютерная техника (id=3)

            // TV и видео (id=4)
            [
                'name' => 'Телевизоры',
                'parent_id' => 4,
            ],
            [
                'name' => 'ТВ-антенны',
                'parent_id' => 4,
            ],
            [
                'name' => 'Пульты ДУ',
                'parent_id' => 4,
            ],
            // TV и видео (id=4)

            // Мобильные телефоны и планшеты (id=5)
            [
                'name' => 'Смартфоны',
                'parent_id' => 5,
            ],
            [
                'name' => 'Планшеты',
                'parent_id' => 5,
            ],
            [
                'name' => 'Электронные книги',
                'parent_id' => 5,
            ],
            // Мобильные телефоны и планшеты (id=5)

            // Связь и IP-телефония (id=6)
            [
                'name' => 'Факсы',
                'parent_id' => 6,
            ],
            [
                'name' => 'Радиотелефоны',
                'parent_id' => 6,
            ],
            [
                'name' => 'Проводные телефоны',
                'parent_id' => 6,
            ],
            // Связь и IP-телефония (id=6)

            // Защитные аксессуары и крепления (id=7)
            [
                'name' => 'Чехлы для планшетов',
                'parent_id' => 7,
            ],
            [
                'name' => 'Чехлы для мобильных телефонов',
                'parent_id' => 7,
            ],
            [
                'name' => 'Защитные стекла и пленки для мобильных телефонов',
                'parent_id' => 7,
            ],
            // Защитные аксессуары и крепления (id=7)

            // Ноутбуки и аксессуары (id=8)
            [
                'name' => 'Ноутбуки',
                'parent_id' => 8,
            ],
            [
                'name' => 'Подставки для ноутбуков',
                'parent_id' => 8,
            ],
            [
                'name' => 'Сумки для ноутбуков',
                'parent_id' => 8,
            ],
            // Ноутбуки и аксессуары (id=8)

            // Компьютеры и ПО (id=9)
            [
                'name' => 'Персональные компьютеры',
                'parent_id' => 9,
            ],
            [
                'name' => 'Программное обеспечение',
                'parent_id' => 9,
            ],
            // Компьютеры и ПО (id=9)


            // Периферия для ПК и аксессуары (id=10)
            [
                'name' => 'Мониторы',
                'parent_id' => 10,
            ],
            [
                'name' => 'Мышки',
                'parent_id' => 10,
            ],
            [
                'name' => 'Клавиатуры',
                'parent_id' => 10,
            ],
            // Периферия для ПК и аксессуары (id=10)
        ];
    }

    /**
     * @throws Exception
     */
    public function actionInit(): void
    {
//        Yii::$app->db->createCommand()->truncateTable('category')->execute();

        foreach ($this->getCategories() as $category) {
            $categoryModel = new Category($category);
            $categoryModel->save();
        }
    }
}
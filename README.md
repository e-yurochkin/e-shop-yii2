## Требования к ПО

Должны быть установленны docker и docker compose

## Установка проекта

1) Клонировать репозиторий на локальную машину
2) Перейти в папку проекта
3) Скопировать .env-dist в .env
4) Инициализировать проект командой в консоли:
    - docker compose run --rm php-dev init
5) Проверить и если нужно настроить подключение к БД в файле common/config/main-local.php
6) Накатить миграции и заполнить БД начальными данными командой:
    - docker compose run --rm php-dev yii db/init
7) Если начальные данные не нужны, то накатить миграции командами:
    - docker compose run --rm php-dev yii migrate
    - docker compose run --rm php-dev yii migrate --migrationPath=@yii/rbac/migrations

## Тестирование

1) Проверить и если нужно настроить подключение к тестовой БД в файле common/config/test-local.php
2) Накатить миграции и заполнить тестовую БД начальными данными командой:
    - docker compose run --rm php-dev ./yii_test test-db/init
3) Запуск всех тестов api:
    - docker compose run --rm php-dev codecept run backend/tests/api
4) Запуск конкретного теста api:
    - docker compose run --rm php-dev codecept run backend/tests/api/v1/AuthCest
5) Запуск одного метода (теста) api:
    - docker compose run --rm php-dev codecept run backend/tests/api/v1/AuthCest::имя_метода

<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Advanced Project Template</h1>
    <br>
</p>

Yii 2 Advanced Project Template is a skeleton [Yii 2](https://www.yiiframework.com/) application best for
developing complex Web applications with multiple tiers.

The template includes three tiers: front end, back end, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![build](https://github.com/yiisoft/yii2-app-advanced/workflows/build/badge.svg)](https://github.com/yiisoft/yii2-app-advanced/actions?query=workflow%3Abuild)

DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```


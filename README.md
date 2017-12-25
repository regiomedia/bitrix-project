# bitrix-project-stub
Заготовка для новых проектов на Битрикс

Предыдущую версию смотреть [здесь](https://github.com/regiomedia/bitrix-project/tree/complex-v)

## Быстрый старт

Стандартно установить или развернуть из бекапа копию Битрикса.

Клонировать репозиторий (за пределами публичной директории веб-сервера).

Установить зависимости:
```sh
composer install && npm install
```

Перенести в корень клонированного проекта содержимое директорий `bitrix`, `upload` и (выборочно) `local`.

В директорию `sites/s1` перенести публичные файлы сайта.

Настроить вебсервер для работы с директорией `sites/s1` либо сделать симлинк вида

```sh
/home/bitrix/www -> /home/bitrix/projectname/sites/s1
```

[Настроить шаблонизатор Twig](https://github.com/maximaster/tools.twig/blob/master/docs/configuration.md)

[Установить модуль миграций](https://github.com/arrilot/bitrix-migrations#%D0%A3%D1%81%D1%82%D0%B0%D0%BD%D0%BE%D0%B2%D0%BA%D0%B0)

Доустановить модуль [Базовых компонентов]((https://github.com/bitrix-expert/bbc)). Композер только загружает необходимые 
файлы. Необходимо выполнить установку модуля в административном интефейсе: 

`Marketplace > Установленные решения > ББК (bex.bbc)`


## Бэкенд

Composer и PSR-4 автозагрузка классов из директории `local/classes`. Пространство имен `\Local\ `

### Используемые пакеты:

- [arrilot/bitrix-migrations](https://github.com/arrilot/bitrix-migrations)
- [arrilot/bitrix-models](https://github.com/arrilot/bitrix-models)
    - [illuminate/database](https://github.com/illuminate/database)
- [bitrix-expert/bbc](https://github.com/bitrix-expert/bbc)
- [maximaster/tools.twig](https://github.com/maximaster/tools.twig)
- [kint-php/kint](https://github.com/kint-php/kint) и [kint-php/kint-twig](https://github.com/kint-php/kint-twig)  

### Контроль качества

Для проверки пхп-кода используется [squizlabs/PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer).

Код проверятся в соответствии с набором правил, описанных в файле [ruleset.xml](ruleset.xml).

На данный момент, это стандарт PSR-2 
([рус.](https://svyatoslav.biz/misc/psr_translation/#_PSR-2)/[англ.](http://www.php-fig.org/psr/psr-2/)),
а также наличие PHPDoc-комментариев.

Проверяются файлы из директорий [local/classes](local/classes) и [local/components](local/components) 
(за исключением файлов `template.php`)

Проверка осуществляется командой (это запуск утилиты `phpcs` с предустановленными параметрами) 

```sh
composer run lint:php
```

Также есть возможность исправить часть обнаруженных ошибок утилитой `phpcbf`

```sh
composer run fix:php
```




## Фронтенд

В качестве "сборщика" изпользуется [symfony/webpack-encore](https://github.com/symfony/webpack-encore). 

По-умолчанию файлы фронтенда должны располагаться в директории `local/assets`.

Это можно переопределить в файле конфигурации [webpack.config.js](./webpack.config.js) 

Основные команды:

```sh
npm run encore -- dev          # запустить сборку один раз
npm run encore -- dev --watch  # запустить сборку в режиме слежения за файлами
npm run encore -- production   # запустить сборку для продакшена
```



### Технологии

- SCSS ([рус.](https://sass-scss.ru/guide/)/[англ.](http://sass-lang.com/guide))
- "Современный" Javascript ([рус](https://learn.javascript.ru/es-modern)/[англ](https://github.com/metagrover/ES6-for-humans))
    - [DOM-based Router](https://github.com/roots/sage/blob/master/resources/assets/scripts/util/Router.js)
    - [Vue JS](https://vuejs.org/)
    
### Контроль качества

JS-файлы проверяются на соответствие стандарту [airbnb](https://github.com/airbnb/javascript) 
утилитой [ESLint](https://eslint.org). Конфигурация линтера - файл [.eslintrc](.eslintrc)

```sh
npm run lint:scripts  # показать ошибки
npm run fix:scripts   # исправить ошибки
```

SCSS-файлы проверяются утилитой [stylelint](https://stylelint.io/). 
Основа - набор правил [sass-guidelines](https://github.com/bjankord/stylelint-config-sass-guidelines). 
Конфигурация - файл [.stylelintrc](.stylelintrc)

```sh
npm run lint:styles  # показать ошибки
npm run fix:styles   # исправить ошибки
```

За исправление стилевых файлов отвечает пакет [stylefmt](https://github.com/morishitter/stylefmt)


## Разное

[Деплой приложения](https://github.com/regiomedia/bitrix-project/wiki/%D0%94%D0%B5%D0%BF%D0%BB%D0%BE%D0%B9-%D0%BF%D1%80%D0%B8%D0%BB%D0%BE%D0%B6%D0%B5%D0%BD%D0%B8%D1%8F)
 
[Гайдлайн разработки Битрикс-проекта](https://github.com/regiomedia/bitrix-project/wiki/%D0%93%D0%B0%D0%B9%D0%B4%D0%BB%D0%B0%D0%B9%D0%BD)

### Для пользователей [Phabricator](https://www.phacility.com/phabricator/)

В файле [.arclint](.arclint) настроены основные проверки кода, в том числе описанные выше.


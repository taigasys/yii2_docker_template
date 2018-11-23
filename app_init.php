#!/usr/bin/env php
<?php
/**
 * Project init wizard
 *
 * @link http://taiga.systems
 * @copyright Copyright (c) 2018 Taiga Systems
 * @author Pavel Pavlenko region23@gmail.com
 */

print("*** Первичная инициализация проекта ***\n\n");

do {
    $projectName = readline("Введи название проекта на английском языке\n>>> ");
} while ('' === $projectName);

do {
    $mysqlRootPassword = readline("Придумай root-пароль для подключения к MySQL\n>>> ");
} while ('' === $mysqlRootPassword);

do {
    $mysqlPassword = readline("Придумай пароль для пользователя user для подключения к MySQL\n>>> ");
} while ('' === $mysqlPassword);

do {
    $projectType = readline("Создать новый проект (1) или инициализировать существующий проект (2)?\n>>> ");
} while ('1' !== $projectType && '2' !== $projectType);

do {
    $serverType = readline("Проект инициализируется для боевого сервера (prod) или компьютера разработчика (dev)?\n>>> ");
} while ('dev' !== $serverType && 'prod' !== $serverType);

$appExist = false;
if (file_exists("./app") && is_dir("./app")) {
    $appExist = true;
}

if ('1' === $projectType && !$appExist) {
    do {
        $projectPort = readline("Введи порт проекта. Будет открываться по адресу https://localhost:port\n>>> ");
    } while ('' === $projectPort);

    do {
        $phpmyadminPort = readline("Введи порт для PhpMyAdmin. Будет открываться по адресу https://localhost:port\n>>> ");
    } while ('' === $phpmyadminPort);

    shell_exec('composer create-project --prefer-dist yiisoft/yii2-app-basic app');
} elseif ('2' === $projectType) {
    readline("Создайте папку app/ и переместите в неё ваш Yii2-проект. После этого нажмите клавишу Enter.");
    shell_exec('ls');
    # Устанавливаем правильные права
    shell_exec('chmod -R 777 app/runtime');
    shell_exec('chmod -R 777 app/web/assets');
    #shell_exec('chmod -R 755 app/web/vendor');
    shell_exec('chmod 755 app/yii');
}

# Чистим от мусора папку с проектом
shell_exec("rm -rf app/vagrant app/.gitignore app/docker-compose.yml app/LICENSE.md app/README.md app/Vagrantfile");

# Вариант для Dev
if ('dev' === $serverType) {
    shell_exec("cp -f templates/Caddyfile.dev docker/Caddyfile");
    shell_exec("cp -f templates/db.php app/config/db.php");
} elseif ('prod' === $serverType) {
    # Вариант для Production
    shell_exec("cp -f templates/Caddyfile.prod docker/Caddyfile");
    shell_exec("cp -f templates/db.php.prod app/config/db.php");

    do {
        $projectDomain = readline("Введи название домена, на котором будет располагаться проект (без http://)\n >>> ");
    } while ('' === $projectDomain);

    do {
        $phpmyadminDomain = readline("Введи название домена, на котором будет располагаться PhpMyAdmin (без http://)\n >>> ");
    } while ('' === $phpmyadminDomain);
}

# Операции одинаковые для обоих серверов
# Скопировать app/runtime/.gitignore в app/vendor/.gitignore
shell_exec("cp -f app/runtime/.gitignore app/vendor/.gitignore");
shell_exec("cp -f templates/docker-compose.yml docker-compose.yml");
shell_exec("cp -f templates/update.sh update.sh");


$stringToReplace = [
    "{PROJECT_NAME}" => $projectName,
    "{MYSQL_ROOT_PASSWORD}" => $mysqlRootPassword,
    "{MYSQL_PASSWORD}" => $mysqlPassword,
    "{PHPMYADMIN_DOMAIN}" => $phpmyadminDomain,
    "{PROJECT_DOMAIN}" => $projectDomain,
    "{PROJECT_PORT}" => $projectPort,
    "{PHPMYADMIN_PORT}" => $phpmyadminPort
];

$replaceInFiles = [
    'docker-compose.yml',
    'app/config/db.php',
    'docker/Caddyfile',
    'update.sh'
];


foreach ($replaceInFiles as $filename) {
    //read the entire string
    $str = file_get_contents($filename);

    if ('prod' === $serverType) {
        $str = str_replace('- {PROJECT_PORT}:{PROJECT_PORT}', '', $str);
        $str = str_replace('- {PHPMYADMIN_PORT}:{PHPMYADMIN_PORT}', '', $str);
        $str = str_replace('#restart: always', 'restart: always', $str);
    }

    foreach ($stringToReplace as $oldVal => $newVal) {
        $str = str_replace($oldVal, $newVal, $str);
    }

    //write the entire string
    file_put_contents($filename, $str);
}


# Накатываем все зависимости composer
shell_exec("composer install --ignore-platform-reqs --no-scripts");

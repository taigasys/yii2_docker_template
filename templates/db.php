<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host={PROJECT_NAME}_mysql_db;dbname={PROJECT_NAME}',
    'username' => 'user',
    'password' => '{MYSQL_PASSWORD}',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];

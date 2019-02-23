<?php

/**
 * Engine PHP
 * Инициализация движка
 */
// error_reporting (E_ALL);
ini_set('display_errors', 'On');
ini_set('html_errors', 0);
error_reporting(-1);

require_once(__DIR__ . '/lib/Loader.php');
new Controller($_GET);

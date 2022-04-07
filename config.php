<?php
// Esse arquivo será chamado ao acessarmos o index.php

// Trantando erros para debug -> desativar em produção
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);



// Criando globais para utilização do banco de dados
define(HOST, 'localhost');
define(BANCO, 'api-rest-php-nativo');
define(USER, 'root');
define(SENHA, '');

// Abreviando global DIRECTORY_SEPARATOR (barra invertida)
define(DS, DIRECTORY_SEPARATOR);
// Abreviando global de diretório
define(DIR_APP, __DIR__);
// Diretório do APP
define(DIR_PROJETO, 'api-rest-php-nativo');

// Incluindo o autoload
if (file_exists('autoload.php')){
    include 'autoload.php';
}else{
    echo "Erro ao incluir arquivo de configuração!";
    exit;
}


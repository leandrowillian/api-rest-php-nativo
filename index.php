<?php

// Incluindo arquivo de configurações
include 'config.php';
// namespaces
use \Validator\RequestValidator;
use \Util\RotasUtil;


// Tratamento de exceções
try{
    // Instanciando a classe que valida a requisição
        // Paramentro estamos passando o retorno da função estatica getRotas, da classe RotasUtil, que retorna um array contendo a "rota", "recurso", "id" e "metodo", para assim validarmos todas as infos da requisição
        $requestValidator = new RequestValidator(RotasUtil::getRotas());
    $retorno = $requestValidator->processaRequest();
}catch(Exception $e){
    echo $e->getMessage();
}
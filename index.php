<?php

// Incluindo arquivo de configurações
include 'config.php';
// namespaces
use Validator\RequestValidator;
use Util\RotasUtil;
use Util\ConstantesGenericasUtil;
use Util\JsonUtil;


// Tratamento de exceções
try{
    // Instanciando a classe que valida a requisição
        // Paramentro estamos passando o retorno da função estatica getRotas, da classe RotasUtil, que retorna um array contendo a "rota", "recurso", "id" e "metodo", para assim validarmos todas as infos da requisição
        $requestValidator = new RequestValidator(RotasUtil::getRotas());
    $retorno = $requestValidator->processaRequest();

    // Tratando o retorno que será tratado para ser convertido em Json
    $jsonUtil = new JsonUtil();
    $jsonUtil->processarArrayParaRetornar($retorno);
    
}catch(Exception $e){
    echo json_encode([
        ConstantesGenericasUtil::TIPO => ConstantesGenericasUtil::TIPO_ERRO,
        ConstantesGenericasUtil::RESPOSTA => $e->getMessage()
    ], JSON_THROW_ON_ERROR);
    exit;
}

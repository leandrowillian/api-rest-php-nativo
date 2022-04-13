<?php

namespace Util;

use \JsonException;

class JsonUtil
{

    public static function tratarCorpoRequisicaoJson()
    {
        try{
            // Atribuindo o que foi enviado na requisicao via post à variavel $postJson. json_decode para converter em um object php ou em um array caso o segundo parametro for "true"
            $postJson = json_decode(file_get_contents('php://input'), true);
            
        }catch(JsonException $e){
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_JSON_VAZIO);
        }

        // Verificando se o json é um array e se ele é maior que 0
        if (is_array($postJson) && count($postJson) > 0){
            return $postJson;
        }

    }


}
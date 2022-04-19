<?php

namespace Util;

use \JsonException;

class JsonUtil
{

    // Função responsável por receber o nosso arryay do retorno da requisição e tratar e converte-lo em Json
    public function processarArrayParaRetornar($retorno)
    {
        $dados = [];
        $dados[ConstantesGenericasUtil::TIPO] = ConstantesGenericasUtil::TIPO_ERRO;

        // Verificando se o que foi passado por parametro é realmente um array e se há posições nesse array
        if(is_array($retorno) && count($retorno) > 0 || strlen($retorno) > 10){
            $dados[ConstantesGenericasUtil::TIPO] = ConstantesGenericasUtil::TIPO_SUCESSO;
            $dados[ConstantesGenericasUtil::RESPOSTA] = $retorno;
        }

        $this->retornarJson($dados);
    }


    private function retornarJson($json)
    {
        // Alterando o content-type no header da requisição
        header('Content-Type: application/json');
        // Removendo o cache da resposta, sem cache e sem armazenamento
        header('Cache-Control: no-cache, no-store, must-revalidate');
        // Informando os métodos que a nossa aplicação aceita
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

        echo json_encode($json);
        exit;
        
    }


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
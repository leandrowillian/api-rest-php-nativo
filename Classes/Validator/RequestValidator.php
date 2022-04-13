<?php

// DECLARANDO NAMESPACE
namespace Validator;

// Declarando os uses
use Util\ConstantesGenericasUtil;
use Util\JsonUtil;
use Repository\TokensAutorizadosRepository;

class RequestValidator 
{
    // Declarando atributos
    private $request;
    private array $dadosRequest;
    private object $tokensAutorizadosRepository;

    // Constantes
    const GET = 'GET';
    const DELETE = 'DELETE';

    public function __construct($request)
    {
        $this->request = $request;
        $this->tokensAutorizadosRepository = new TokensAutorizadosRepository();
    }

    public function processaRequest()
    {
        // Utilizando utf8_encode para o JSON ficar formatado com esse charset
        $retorno = ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA;
        // if para checar se o método que foi requisitado está na nossa constante de "métodos suportados". terceiro parametro "true" para que verifique se o valor buscado é identido aos valores que contém no array

        if (in_array($this->request['metodo'],ConstantesGenericasUtil::TIPO_REQUEST, true)){
            $retorno = $this->direcionarRequest();
        }

        return $retorno;

    }

    // Método privado pois somente essa classe pode chamá-lo
    private function direcionarRequest()
    {
        // Declaramos esse if para separadar quais requisições precisamos de um corpo na requisição. GET e DELETE não precisam, pois o GET só vai listar e o DELETE vai excluir de acordo com o id passado já na url
        if ($this->request['metodo'] !== self::GET && $this->request['metodo'] !== self::DELETE){
            $this->dadosRequest = JsonUtil::tratarCorpoRequisicaoJson();
        }

        $this->tokensAutorizadosRepository->validarToken(getallheaders()['Authorization']);

    }
    
}
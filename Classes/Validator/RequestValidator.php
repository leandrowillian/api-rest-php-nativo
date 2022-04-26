<?php

// DECLARANDO NAMESPACE
namespace Validator;

// Declarando os uses
use Util\ConstantesGenericasUtil;
use Util\JsonUtil;
use Repository\TokensAutorizadosRepository;
use Service\UsuariosService;
use InvalidArgumentException;

class RequestValidator 
{
    // Declarando atributos
    private $request;
    
    /**
     * Summary of 
     * @var array
     */
    private array $dadosRequest = [];

    /**
     * Summary of 
     * @var object/TokensAutorizadosRepository
     */
    private object $tokensAutorizadosRepository;

    // Constantes
    const GET = 'GET';
    const DELETE = 'DELETE';
    const USUARIOS = 'USUARIOS';

    /**
     * Summary of __construct
     * @param mixed $request
     */
    public function __construct($request)
    {
        $this->request = $request;
        $this->tokensAutorizadosRepository = new TokensAutorizadosRepository();
    }
    
    /**
     * Summary of processaRequest
     * @return string
     */
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
    /**
     * Summary of direcionarRequest
     * @return mixed
     */
    private function direcionarRequest()
    {
        // Declaramos esse if para separadar quais requisições precisamos de um corpo na requisição. GET e DELETE não precisam, pois o GET só vai listar e o DELETE vai excluir de acordo com o id passado já na url
        if ($this->request['metodo'] !== self::GET && $this->request['metodo'] !== self::DELETE){
            $this->dadosRequest = JsonUtil::tratarCorpoRequisicaoJson();
        }
        
        $this->tokensAutorizadosRepository->validarToken(getallheaders()['Authorization']);

        // Pegando o método que está sendo chamado na requisição e atribuindo à variável $metodo, para que possamos criar uma função variável
        $metodo = $this->request['metodo'];
        // Criando o que no php tem o que antes tinha o nome de funções variáveis. O php irá chamar uma função com o que estará atribuido na variável.
        return $this->$metodo();

    }

    //essa função será chamada pela função variável, caso o metodo no request seja "get"
    /**
     * Summary of get
     * @return mixed
     */
    private function get()
    {
        $retorno = ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA;

        if(in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_GET, true)){
            switch ($this->request['rota']){
                case self::USUARIOS:
                    /**
                     * @var mixed $usuariosService
                     * Enviando os parametros da request como parametro do contrutor da classe UsuariosService e será atribuido ao atributo $dados;
                     */
                    $usuariosService = new UsuariosService($this->request);
                    $retorno = $usuariosService->validarGet();
                    break;
                default:
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
                    
            }
        }

        return $retorno;


    }

    //essa função será chamada pela função variável, caso o metodo no request seja "delete"
    /**
     * Summary of delete
     * @return mixed
     */
    private function delete()
    {
        $retorno = ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA;

        if(in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_DELETE, true)){
            switch ($this->request['rota']){
                case self::USUARIOS:
                    /**
                     * @var mixed $usuariosService
                     * Enviando os parametros da request como parametro do contrutor da classe UsuariosService e será atribuido ao atributo $dados;
                     */
                    $usuariosService = new UsuariosService($this->request);
                    $retorno = $usuariosService->validarDelete();
                    break;
                default:
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
                    
            }
        }

        return $retorno;


    }

    /**
     * Summary of post
     * @return mixed
     */
    private function post()
    {
        $retorno = ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA;

        if(in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_POST, true)){
            switch ($this->request['rota']){
                case self::USUARIOS:
                    /**
                     * @var mixed $usuariosService
                     * Enviando os parametros da request como parametro do contrutor da classe UsuariosService e será atribuido ao atributo $dados;
                     */
                    $usuariosService = new UsuariosService($this->request);
                    // Método resposável por setar os dados vindos da request
                    $usuariosService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $usuariosService->validarPost();
                    break;
                default:
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
                    
            }
        }

        return $retorno;


    }

    /**
     * Summary of put
     * @return mixed
     */
    private function put()
    {
        $retorno = ConstantesGenericasUtil::MSG_ERRO_TIPO_ROTA;

        if(in_array($this->request['rota'], ConstantesGenericasUtil::TIPO_PUT, true)){
            switch ($this->request['rota']){
                case self::USUARIOS:
                    /**
                     * @var mixed $usuariosService
                     * Enviando os parametros da request como parametro do contrutor da classe UsuariosService e será atribuido ao atributo $dados;
                     */
                    $usuariosService = new UsuariosService($this->request);
                    // Método resposável por setar os dados vindos da request
                    $usuariosService->setDadosCorpoRequest($this->dadosRequest);
                    $retorno = $usuariosService->validarPut();
                    break;
                default:
                    throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
                    
            }
        }

        return $retorno;


    }


    
}
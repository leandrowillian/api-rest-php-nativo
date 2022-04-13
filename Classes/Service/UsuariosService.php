<?php

namespace Service;
use Repository\UsuariosRepository;
use InvalidArgumentException;
use Util\ConstantesGenericasUtil;

class UsuariosService
{
    // Tabela que será consultada no banco e também o nome da nossa rota
    public const TABELA = 'usuarios';
    // Nome do recurso, que vem também pela rota, como segundo parametro do array da request
    public const RECURSOS_GET = ['listar'];
    private array $dados;

    /**
     * Summary of 
     * @var mixed
     */
    private object $usuariosRepository;

    /**
     * Summary of __construct
     * @param mixed $dados
     */
    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->usuariosRepository = new UsuariosRepository();
    }


    public function validarGet()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        
        /**
         * Procura se há esse recurso na constante RECURSOS_GET que declaramos na criação da classe
         */
        if(in_array($recurso, self::RECURSOS_GET, true)){
            /**
             * @var mixed $retorno
             * Se existir id passado na rota (se for >0), chamaremos o método de listar por chave, no caso id
             * Se não existir o id na rota, chamaremos um método para variável dependendo da rota acessada
             */
            $retorno = $this->dados['id'] > 0 ? $this->getOneByKey() : $this->$recurso(); 

        }else{
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

    }

    // Lista o usuario pelo id
    private function getOneByKey()
    {

    }

    // Lista todos os usuarios da tabela usuarios
    private function listar()
    {
        /**
         * getMySql retorna a instancia da classe "MySql", que por sua vez, possui o método "getAll" que retorna todos os dados de uma tabela que é informada por parametro na sua chamada
         */
        return $this->usuariosRepository->getMySql()->getAll(self::TABELA);;
        
    }



    


}
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
    public const RECURSOS_DELETE = ['deletar'];
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

        if($retorno === null){
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        return $retorno;


    }

    public function validarDelete()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        
        /**
         * Procura se há esse recurso na constante RECURSOS_DELETE que declaramos na criação da classe
         */
        if(in_array($recurso, self::RECURSOS_DELETE, true)){
            /**
             * @var mixed $retorno
             * Se existir id passado na rota (se for > 0), chamaremos o método de deletar por chave, no caso id
             * Se não existir o id na rota, chamaremos um método para variável dependendo da rota acessada
             */
            if($this->dados['id'] > 0){
                $retorno = $this->$recurso();
            }else{
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
            }

        }else{
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        if($retorno === null){
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }

        return $retorno;


    }

    // Lista o usuario pelo id
    private function getOneByKey()
    {
        return $this->usuariosRepository->getMySql()->getOneByKey(self::TABELA, $this->dados['id']);

    }

    // Lista todos os usuarios da tabela usuarios
    private function listar()
    {
        /**
         * getMySql retorna a instancia da classe "MySql", que por sua vez, possui o método "getAll" que retorna todos os dados de uma tabela que é informada por parametro na sua chamada
         */
        return $this->usuariosRepository->getMySql()->getAll(self::TABELA);
        
    }

    // Método de deleção de um usuário por id
    private function deletar()
    {
        return $this->usuariosRepository->getMySql()->delete(self::TABELA, $this->dados['id']);
    }



    


}
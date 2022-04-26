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
    public const RECURSOS_POST = ['cadastrar'];
    public const RECURSOS_PUT = ['atualizar'];

    /**
     * Summary of 
     * @var array
     */
    private array $dados;

    /**
     * Summary of 
     * @var array
     */
    private array $dadosCorpoRequest = [];

    /**
     * Summary of 
     * @var object/UsuariosRepository
     */
    private object $usuariosRepository;

    /**
     * Summary of __construct
     * @param array $dados
     */
    public function __construct($dados = [])
    {
        $this->dados = $dados;
        $this->usuariosRepository = new UsuariosRepository();
    }

    /**
     * Summary of validarGet
     * @return mixed
     */
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

        $this->validarRetornoRequest($retorno);

        return $retorno;


    }

    /**
     * Summary of validarDelete
     * @return mixed
     */
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
             */
            $retorno = $this->validarIdObrigatorio($recurso);

        }else{
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validarRetornoRequest($retorno);

        return $retorno;


    }

    /**
     * Summary of validarPost
     * @return mixed
     */
    public function validarPost()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        
        /**
         * Procura se há esse recurso na constante RECUSROS_POST que declaramos na criação da classe
         */
        if(in_array($recurso, self::RECURSOS_POST, true)){
            $retorno = $this->$recurso();

        }else{
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validarRetornoRequest($retorno);

        return $retorno;


    }

    /**
     * Summary of validarPut
     * @return mixed
     */
    public function validarPut()
    {
        $retorno = null;
        $recurso = $this->dados['recurso'];
        
        /**
         * Procura se há esse recurso na constante RECURSOS_DELETE que declaramos na criação da classe
         */
        if(in_array($recurso, self::RECURSOS_PUT, true)){
            /**
             * @var mixed $retorno
             * Se existir id passado na rota (se for > 0), chamaremos o método de atualizar por chave, no caso id
             */
            $retorno = $this->validarIdObrigatorio($recurso);

        }else{
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_RECURSO_INEXISTENTE);
        }

        $this->validarRetornoRequest($retorno);

        return $retorno;


    }


    public function setDadosCorpoRequest($dadosRequest)
    {
        $this->dadosCorpoRequest = $dadosRequest;

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

    // Método para cadastrar um usuário
    private function cadastrar()
    {   
        // Criando uma lista para não precisar declarar e atribuir um em cada linha
        [$login, $senha] = [$this->dadosCorpoRequest['login'], $this->dadosCorpoRequest['senha']];

        if($login && $senha){
            if($this->usuariosRepository->insertUser($login, $senha) > 0){
                // Pegando o último id do banco para sabermos qual o id do user que acabamos de inserir. Utilziamos a função lastInsertId que já é nativa do PDO
                $idInserido = $this->usuariosRepository->getMySql()->getDb()->lastInsertId();

                // Como utilizamos a função "beginTransaction", temos que usar a função "commit", para assim informarmos ao DB que as alterações estão corretas e ele pode atualizar o banco, pois elas estão corretas
                $this->usuariosRepository->getMySql()->getDb()->commit();

                // retornando id inserido
                return ['id_inserido' => $idInserido];

            }

            // Assim como o commit é utilizado quando está tudo certo com a requisição e é um aviso para o banco salvar as alterações, quando algo dá errado, utilizamos o rollBack para que o banco volte as alterações que foram realizadas e não salve essas alterações
            $this->usuariosRepository->getMySql()->getDb()->rollBack();
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);

        }

        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_LOGIN_SENHA_OBRIGATORIO);
        
      
    }

    private function atualizar()
    {
        if($this->usuariosRepository->updateUser($this->dados['id'], $this->dadosCorpoRequest) > 0){
            $this->usuariosRepository->getMySql()->getDb()->commit();
            return ConstantesGenericasUtil::MSG_ATUALIZADO_SUCESSO;
        }

        $this->usuariosRepository->getMySql()->getDb()->rollBack();
        throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_NAO_AFETADO);


    }

    /**
     * Summary of validarIdObrigatorio
     * @param mixed $recurso
     * @return mixed
     */
    private function validarIdObrigatorio($recurso)
    {

        if($this->dados['id'] > 0){
            $retorno = $this->$recurso();
        } else{
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_ID_OBRIGATORIO);
        }
        
        return $retorno;

    }

    /**
     * Summary of validarRetornoRequest
     * @param mixed $retorno
     * @return void
     */
    private function validarRetornoRequest($retorno)
    {
        if ($retorno === null){
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_GENERICO);
        }
    }



    


}
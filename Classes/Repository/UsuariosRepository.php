<?php

// Classes 'repository' são classes que fazem referencia à uma tabela no banco de dados

namespace Repository;

use DB\MySql;


class UsuariosRepository
{

    private object $mySql;
    public const TABELA = "usuarios";

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->mySql = new MySql();
    }

    public function insertUser($login, $senha)
    {

        $sql = "INSERT INTO " . self::TABELA . " (login, senha) VALUES (:l, :s)";
        // beginTransaction desativa o modo de envio automático. Enquanto o modo de envio automático estiver desativado, modificações feitas no banco de dados por meio da instância do objeto PDO não serão enviadas até que você finalize a transação chamando PDO::commit(). Chamar PDO::rollBack() reverterá todas as alterações no banco de dados e retornará a conexão para o modo de envio automático.
        $this->mySql->getDb()->beginTransaction();
        $stmt = $this->mySql->getDb()->prepare($sql);
        $stmt->bindParam(":l", $login);
        $stmt->bindParam(":s", $senha);
        $stmt->execute();
        return $stmt->rowCount();

    }

    
    /**
     * Summary of getMySql
     * @return MySql|object
     */
    public function getMySql()
    {
        return $this->mySql;
    }
    


}
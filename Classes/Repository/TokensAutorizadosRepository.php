<?php

// Classes 'repository' são classes que fazem referencia à uma tabela no banco de dados

namespace Repository;

use DB\MySql;
use InvalidArgumentException;
use Util\ConstantesGenericasUtil;


class TokensAutorizadosRepository
{

    private object $mySql;
    public const TABELA = "tokens_autorizados";

    /**
     * Summary of __construct
     */
    public function __construct()
    {
        $this->mySql = new MySql();
    }

    /**
     * Summary of validarToken
     * @param mixed $token
     * @return void
     */
    public function validarToken($token)
    {

        $token = str_replace([" ", "Bearer"], "", $token);
        if ($token){
            $consultaToken = "SELECT id FROM " . self::TABELA . ' WHERE token = :t AND STATUS = :s';
            $stmt = $this->getMySql()->getDb()->prepare($consultaToken);
            $stmt->bindValue(":t", $token);
            $stmt->bindValue(":s", ConstantesGenericasUtil::SIM);
            $stmt->execute();

            if($stmt->rowCount() !== 1){
                header('HTTP/1.1 401 Unauthorized');
                throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TOKEN_NAO_AUTORIZADO);
            }
            


        }else{
            throw new InvalidArgumentException(ConstantesGenericasUtil::MSG_ERRO_TOKEN_VAZIO);
        }

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
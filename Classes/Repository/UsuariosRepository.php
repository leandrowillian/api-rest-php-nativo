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

    
    /**
     * Summary of getMySql
     * @return MySql|object
     */
    public function getMySql()
    {
        return $this->mySql;
    }
    


}
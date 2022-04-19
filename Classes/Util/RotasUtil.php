<?php

namespace Util;


class RotasUtil
{

    public static function getRotas()
    {
        $urls = self::getUrls();
        // Criando um array
        $request = array();
        
        // Atribuindo a rota valor à posição rota do array e colocando em maiúsculo
        $request['rota'] = strtoupper($urls[0]);
        
        // Atribuindo o recurso/função à posicao recurso do array
            // Operador de coalescência nula - a partir do php 7.*
        $request['recurso'] = $urls[1] ?? null;

        // Id passado como 3° "parametro" na uri -> será utilizado para listar/atualizar/deletar dado especifico pelo id
        $request['id'] = $urls[2] ?? null;

        // Metodo requisitado -> POST/GET/PUT/DELETE
        $request['metodo'] = $_SERVER['REQUEST_METHOD'];
       
        return $request;
        
        
    }

    public static function getUrls()
    {

        // echo "<pre>";
        // var_dump($_SERVER);
        // exit;
        // replace na string do indice REQUEST_URI da glovar $_SERVER. Procurando o caminha "/api-rest-php-nativo" e substituindo por nada. Assim teremos apenas a "rota" que o usuário está acessando e depois trataremos
        $uri = str_replace('/'.DIR_PROJETO, '', $_SERVER['REQUEST_URI']);
        
        // função explode na uri quebrando pela barra
        return explode('/', trim($uri, '/'));

    }


}
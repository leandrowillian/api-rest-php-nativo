<?php

// DECLARANDO NAMESPACE
namespace Validator;

class RequestValidator 
{

    // Declarando atributos
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function processaRequest()
    {

        var_dump($this->request);

    }


}
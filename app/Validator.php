<?php

namespace App;

use \Respect\Validation\Validator as Respect;
use \Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
    protected $erros;

    public function validate($request, array $regras)
    {
        foreach($regras as $campo => $regra) {

            try {
                $regra->setName(ucfirst($campo))->assert($request->getParam($campo));
            } catch (NestedValidationException $e) {
                $this->erros[$campo] = $e->getMessages();
            }
        }

        $_SESSION['erros'] = $this->erros;

        return $this;
    }

    public function failed()
    {
        return !empty($this->erros);
    }
}

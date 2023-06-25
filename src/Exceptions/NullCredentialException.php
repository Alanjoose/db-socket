<?php

namespace Alanjoose\Exceptions;

use Exception;

/**
 * Class NullCredentialException
 * @package Src\Exceptions
 */
class NullCredentialException extends Exception
{
    public function __construct()
    {
        $this->message = 'Null passed instead valid argument at required database env vars';
        $this->code = 1;
    }

    public function handle()
    {
        echo "\n<<--NullCredentialException-->> ".$this->message.".\n";
        die("<<--EXCEPTION CODE:-->> ".$this->code);
    }
}

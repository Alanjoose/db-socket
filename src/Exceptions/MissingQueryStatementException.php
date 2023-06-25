<?php

namespace Alanjoose\Exceptions;

use Exception;

class MissingQueryStatementException extends Exception
{
    public function __construct($missingStatement, $extra = '')
    {
        $this->message = 'The query doesn\'t contains the '.$missingStatement.' statement. '.$extra;
        $this->code = 1;
    }

    public function handle()
    {
        echo "\n<<--MissingQueryStatementException-->> ".$this->message.".\n";
        die("<<--EXCEPTION CODE:-->> ".$this->code);
    }
}
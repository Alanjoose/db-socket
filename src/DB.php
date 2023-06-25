<?php

namespace Alanjoose;

use Alanjoose\Traits\StaticQueries;
use Alanjoose\Traits\TransactionsHandler;

/**
 * Class DB is the main database handling facade.
 * @package Src
 */
class DB
{
    use StaticQueries;
    use TransactionsHandler;
}

<?php

namespace Alanjoose\Traits;

use Alanjoose\DBSocket;
use Closure;
use PDOException;

/**
 * Trait TransactionsHandler
 * Here occurrs the transaction controll to use in DB facade.
 * @package Src\Traits
 */
trait TransactionsHandler
{
    /**
     * Use to perform a transaction, the default trys number case deadlock occurs is 3.
     * @param Closure $callback
     * @param int $retrysCaseDeadlock
     */
    public static function runTransaction(Closure $callback, $retrysCaseDeadlock = 3)
    {
        try
        {
            $socket = new DBSocket();
            $connection = $socket->getConnection();
            $connection->beginTransaction();
            $done = false;
            $trys = 0;

            while (!$done && $trys < $retrysCaseDeadlock) {
                $trys++;
                $callback();
                $connection->commit();
                $done = true;
            }

        }
        catch (PDOException $exception)
        {
            $connection->rollback();
            var_dump($exception);
            die(1);
        }
    }
}

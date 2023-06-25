<?php

namespace Alanjoose\Traits;

use Alanjoose\DBSocket;
use Alanjoose\DB;
use PDO;
use PDOException;
use Alanjoose\Exceptions\MissingQueryStatementException;

/**
 * Trait StaticQueries
 * Each method verify if the $query param starts with the respective keyword.
 * @package Src\Traits
 */
trait StaticQueries
{
    use TransactionsHandler;

    /**
     * Use this to perform raw sql statements. !!{Use with caution}!!
     * @param string $query
     * @param array $params
     * @return mixed
     */
    public static function raw(string $query, array $params = [])
    {
        try
        {
            $socket = new DBSocket();
            $statement = $socket->getConnection()
                ->prepare(strtolower($query));

            if(count($params) > 0) {
                foreach ($params as $index => &$param) {
                    $statement->bindParam(($index + 1), $param);
                }
            }

            return $statement->execute();
        }
        catch(PDOException $exception)
        {
            var_dump($exception);
            die(1);
        }
    }

    /**
     * @param string $query
     * @param array $params
     * @return mixed
     */
    public static function insert(string $query, array $params = [])
    {
        try
        {
            if(!str_starts_with(strtolower($query), 'insert')) {
                throw new MissingQueryStatementException('insert');
            }

            $socket = new DBSocket();
            $statement = $socket->getConnection()
                ->prepare(strtolower($query));

            if(count($params) > 0) {
                foreach ($params as $index => &$param) {
                    $statement->bindParam(($index + 1), $param);
                }
            }

            return $statement->execute();
        }
        catch(MissingQueryStatementException $exception)
        {
            $exception->handle();
        }
        catch (PDOException $exception)
        {
            var_dump($exception);
            die(1);
        }
    }

    /**
     * @param string $query
     * @param array $params
     * @return mixed
     */
    public static function select(string $query, array $params = [])
    {
        try
        {
            if(!str_starts_with(strtolower($query), 'select')) {
                throw new MissingQueryStatementException('select');
            }

            $socket = new DBSocket();
            $statement = $socket->getConnection()
                ->prepare(strtolower($query));

            if(count($params) > 0) {
                foreach ($params as $index => &$param) {
                    $statement->bindParam(($index + 1), $param);
                }
            }

            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_OBJ);
        }
        catch(MissingQueryStatementException $exception)
        {
            $exception->handle();
        }
        catch(PDOException $exception)
        {
            var_dump($exception);
        }
    }

    /**
     * To perform conditional update without 'where' enable the $ignoreWhere param.
     * @param string $query
     * @param array $params
     * @param bool $ignoreWhere
     * @return mixed
     */
    public static function update(string $query, array $params = [], bool $ignoreWhere = false)
    {
        try
        {
            if(!str_starts_with(strtolower($query), 'update')) {
                throw new MissingQueryStatementException('update');
            }

            if((strpos(strtolower($query), 'where') == 0) && !$ignoreWhere) {
                throw new MissingQueryStatementException('where', 'For security the query was interrupted.
                Use the $ignoreWhere param to proceed without this.');
            }

            $socket = new DBSocket();
            $statement = $socket->getConnection()
                ->prepare(strtolower($query));

            if(count($params) > 0) {
                foreach ($params as $index => &$param) {
                    $statement->bindParam(($index + 1), $param);
                }
            }

            return $statement->execute();
        }
        catch(MissingQueryStatementException $exception)
        {
            $exception->handle();
        }
        catch(PDOException $exception)
        {
            var_dump($exception);
            die(1);
        }
    }

    /**
     * To perform conditional delete without 'where' use the truncateTable case this have not foreign keys, else use clearTable.
     * @param string $query
     * @param array $params
     * @return mixed
     */
    public static function delete(string $query, array $params = [])
    {
        try
        {
            if(!str_starts_with(strtolower($query), 'delete')) {
                throw new MissingQueryStatementException('delete');
            }

            if((strpos(strtolower($query), 'where') == 0)) {
                throw new MissingQueryStatementException('where', 'For security the query was interrupted.
                If you wants to delete all the table data use the clearTable method.');
            }

            $socket = new DBSocket();
            $statement = $socket->getConnection()
                ->prepare(strtolower($query));

            if(count($params) > 0) {
                foreach ($params as $index => &$param) {
                    $statement->bindParam(($index + 1), $param);
                }
            }

            return $statement->execute();
        }
        catch(MissingQueryStatementException $exception)
        {
            $exception->handle();
        }
        catch(PDOException $exception)
        {
            var_dump($exception);
            die(1);
        }
    }

    /**
     * Use to clear all the table data.
     * @param string $table
     * @return mixed
     */
    public static function clearTable(string $table)
    {
        try
        {
            $socket = new DBSocket();
            $statement = $socket->getConnection()
                ->prepare("delete from {$table}");
            return $statement->execute();
        }
        catch (PDOException $exception)
        {
            var_dump($exception);
            die(1);
        }
    }

    /**
     * Use to clear all the table data.
     * @param string $table
     * @param bool $disableForeignkeyChecks.
     */
    public static function truncateTable(string $table, bool $disableForeignkeyChecks = false)
    {
        try
        {
            DB::runTransaction(function() use ($table, $disableForeignkeyChecks) {

                $statement = 'truncate table '.$table;

                if($disableForeignkeyChecks) {
                    $statement = 'set FOREIGN_KEY_CHECKS = 0; '.$statement.'; set set FOREIGN_KEY_CHECKS = 1;';
                }

                DB::raw($statement);

            });
        }
        catch(PDOException $exception)
        {
            var_dump($exception);
            die(1);
        }
    }
}

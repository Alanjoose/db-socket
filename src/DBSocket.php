<?php

namespace Alanjoose;

use PDO;
use Exception;
use Alanjoose\Exceptions\NullCredentialException;

/**
 * Class DBSocket provides the database connection interface.
 * @package Src
 */
class DBSocket
{
    /**
     * The SGBD software
     * @var $driver
     */
    private $driver;

    /**
     * The database host
     * @var $host
     */
    private $host;

    /**
     * The database port
     * @var $port
     */
    private $port;

    /**
     * The database charset collection
     * @var $charset
     */
    private $charset;

    /**
     * The database name
     * @var $name
     */
    private $name;

    /**
     * The database username login
     * @var $username
     */
    private $username;

    /**
     * The database password login
     * @var $password
     */
    private $password;

    private $options = [
        PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ERRMODE
    ];

    /**
     * Mysql DSN template
     */
    private const MYSQL_DSN = 'mysql:host=HOST;port=PORT;dbname=DBNAME;charset=CHARSET';

    /**
     * Postgres SQL DSN template
     */
    private const PGSQL_DSN = 'pgsql:host=HOST;port=PORT;dbname=DBNAME';

    /**
     * DBSocket constructor.
     * Get the credentials from current env
     */
    public function __construct()
    {
        try
        {
            $this->driver = $_ENV['DB_DRIVER'] ?? 'mysql';
            $this->host = $_ENV['DB_HOST'] ?? 'localhost';
            $this->port = $_ENV['DB_PORT'] ?? 3306;
            $this->charset = $_ENV['DB_CHARSET'] ?? 'utf8';
            $this->name = $_ENV['DB_NAME'];
            $this->username = $_ENV['DB_USERNAME'] ?? 'root';
            $this->password = $_ENV['DB_PASSWORD'];

            $thisObject = get_object_vars($this);

            if(in_array(null, $thisObject)) {
                throw new NullCredentialException();
            }
        }
        catch(NullCredentialException $exception)
        {
            $exception->handle();
        }
        catch(\Exception $exception)
        {
            echo "<<--EXCEPTION-->> ".$exception->getMessage();
            echo "<<--TRACEBACK-->> ".$exception->getTraceAsString();
            die(0);
        }
    }

    /**
     * Replace the DSN markups
     * @param array $markups
     * @param array $targets
     * @param string $subject
     * @return mixed
     */
    private function replaceDSNMarkups(array $markups, array $targets, string $subject)
    {
        return str_replace($markups, $targets, $subject);
    }

    /**
     * Make MYSQL connection
     * @return PDO
     */
    private function mysql()
    {
        try
        {
            $dsn = $this->replaceDSNMarkups(
                ['HOST', 'PORT', 'DBNAME', 'CHARSET'],
                [$this->host, $this->port, $this->name, $this->charset],
                self::MYSQL_DSN
            );

            $pdo = new PDO($dsn, $this->username, $this->password, $this->options);
            return $pdo;
        }
        catch(Exception $exception)
        {
            var_dump($exception);
            die(1);
        }
    }

    /**
     * Make PGSQL connection
     * @return PDO
     */
    private function pgsql()
    {
        try
        {
            $dsn = $this->replaceDSNMarkups(
                ['HOST', 'PORT', 'DBNAME'],
                [$this->host, $this->port, $this->name],
                self::PGSQL_DSN
            );

            $pdo = new PDO($dsn, $this->username, $this->password, $this->options);
            return $pdo;
        }
        catch(Exception $exception)
        {
            var_dump($exception);
        }
    }

    /**
     * Get the connection by object literals
     * @return mixed
     */
    public function getConnection()
    {
        try
        {
            $methodName = (string) $this->driver;
            return $this->$methodName();
        }
        catch(Exception $exception)
        {
            var_dump($exception);
            die(1);
        }
    }
}

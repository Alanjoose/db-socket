<?php declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use Alanjoose\DBSocket;

/**
 * Class DBSocketTest
 */
class DBSocketTest extends TestCase
{
    /**
     * The called function must be instance from DBSocket
     */
    public function testMustBeInstanceFromDBSocket():void
    {
        $this->assertInstanceOf(DBSocket::class, (new DBSocket()));
    }

    /**
     * The called function must be instance from PDO
     */
    public function testMustBeInstanceFromPDO(): void
    {
        $this->assertInstanceOf(PDO::class, (new DBSocket())->getConnection());
    }
}

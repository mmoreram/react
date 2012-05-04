<?php

namespace React\Tests\Socket;

use React\Socket\Connection;

class ConnectionTest extends TestCase
{
    /**
     * @covers React\Socket\Connection::__construct
     */
    public function testConstructor()
    {
        $socket = fopen('php://temp', 'r+');
        $server = $this->createServerMock();

        $conn = new Connection($socket, $server);
    }

    /**
     * @covers React\Socket\Connection::isOpen
     */
    public function testIsOpen()
    {
        $socket = fopen('php://temp', 'r+');
        $server = $this->createServerMock();

        $conn = new Connection($socket, $server);
        $this->assertTrue($conn->isOpen());

        fclose($socket);
        $this->assertFalse($conn->isOpen());
    }

    /**
     * @covers React\Socket\Connection::write
     */
    public function testWrite()
    {
        $socket = fopen('php://temp', 'r+');
        $server = $this->createServerMock();

        $conn = new Connection($socket, $server);
        $conn->write("foo\n");

        rewind($socket);
        $this->assertSame("foo\n", fgets($socket));
    }

    /**
     * @covers React\Socket\Connection::write
     */
    public function testWriteError()
    {
        $socket = "Silly developer, you can't write to to a string!";
        $server = $this->createServerMock();

        $conn = new Connection($socket, $server);
        $conn->on('error', $this->expectCallableOnce());
        $conn->write('Attempting to write to a string');
    }

    /**
     * @covers React\Socket\Connection::close
     */
    public function testClose()
    {
        $socket = fopen('php://temp', 'r+');

        $server = $this->createServerMock();
        $server
            ->expects($this->once())
            ->method('close');

        $conn = new Connection($socket, $server);
        $conn->close();
    }

    private function createServerMock()
    {
        $mock = $this->getMockBuilder('React\Socket\Server')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }
}
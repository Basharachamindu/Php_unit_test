<?php

use PHPUnit\Framework\TestCase;
use App\Login;

class LoginTest extends TestCase {
    private $connection;
    private $login;

    protected function setUp(): void {
        // Create a mock mysqli connection object
        $this->connection = $this->createMock(mysqli::class);

        // Mock the prepare method to return a mock statement
        $stmt = $this->createMock(mysqli_stmt::class);

        $stmt->method('bind_param')->willReturn(true);
        $stmt->method('execute')->willReturn(true);

        $this->stmt = $stmt;

        // Mock the result object for a successful login
        $successResult = $this->createMock(mysqli_result::class);
        $successResult->method('fetch_array')->willReturn(['id' => '1', 'password' => 'admin123']);

        // Mock the result object for a failed login
        $failureResult = $this->createMock(mysqli_result::class);
        $failureResult->method('fetch_array')->willReturn(null);

        // Mock the get_result method to return the appropriate result based on the test case
        $stmt->method('get_result')
            ->will($this->returnCallback(function() use ($successResult, $failureResult) {
                if ($this->currentTestCase === 'testAuthenticateSuccess') {
                    return $successResult;
                } else {
                    return $failureResult;
                }
            }));

        $this->connection->method('prepare')->willReturn($stmt);

        $this->login = new Login($this->connection);
    }

    public function testAuthenticateSuccess() {
        $this->currentTestCase = __FUNCTION__; // Set the current test case
        $id = '1';
        $password = 'admin123';
        $result = $this->login->authenticate($id, $password);
        $this->assertTrue($result);
    }

    public function testAuthenticateFailure() {
        $this->currentTestCase = __FUNCTION__; // Set the current test case
        $id = '1';
        $password = 'wrongpassword';
        $result = $this->login->authenticate($id, $password);
        $this->assertFalse($result);
    }
}

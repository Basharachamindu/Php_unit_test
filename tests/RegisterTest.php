<?php

use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        // Mock the mysqli connection
        $this->conn = $this->createMock(mysqli::class);
        include 'config.php'; // Assuming config.php sets up the $conn variable
        $GLOBALS['conn'] = $this->conn; // Set global connection for the script
    }

    public function testUsernameExists()
    {
        $_SERVER["REQUEST_METHOD"] = "POST";
        $_POST['name'] = 'a';
        $_POST['username'] = 'a';
        $_POST['password'] = 'a';
        $_POST['role'] = 'teacher';

        // Mock the prepare statement for the username check
        $checkStmt = $this->createMock(mysqli_stmt::class);
        $checkStmt->method('execute')->willReturn(true);
        $checkStmt->method('store_result')->willReturn(true);
        $checkStmt->method('num_rows')->willReturn(1);
        $checkStmt->method('close')->willReturn(true);

        $this->conn->method('prepare')->willReturn($checkStmt);

        // Include the script and execute
        ob_start();
        include 'src/register.php'; // Update this path to the actual location of your script
        $output = ob_get_clean();

        $this->assertStringContainsString('Username already exists!', $output);
    }

    public function testRegistrationSuccess()
    {
        $_SERVER["REQUEST_METHOD"] = "POST";
        $_POST['name'] = 'a';
        $_POST['username'] = 'a';
        $_POST['password'] = 'a';
        $_POST['role'] = 'teacher';

        // Mock the prepare statement for the username check
        $checkStmt = $this->createMock(mysqli_stmt::class);
        $checkStmt->method('execute')->willReturn(true);
        $checkStmt->method('store_result')->willReturn(true);
        $checkStmt->method('num_rows')->willReturn(0);
        $checkStmt->method('close')->willReturn(true);

        // Mock the prepare statement for the insert query
        $insertStmt = $this->createMock(mysqli_stmt::class);
        $insertStmt->method('bind_param')->willReturn(true);
        $insertStmt->method('execute')->willReturn(true);
        $insertStmt->method('close')->willReturn(true);

        $this->conn->method('prepare')->willReturnOnConsecutiveCalls($checkStmt, $insertStmt);

        // Include the script and execute
        ob_start();
        include 'src/register.php'; // Update this path to the actual location of your script
        $output = ob_get_clean();

        $this->assertStringContainsString('Registration successful!', $output);
    }

    public function testMissingFields()
    {
        $_SERVER["REQUEST_METHOD"] = "POST";
        $_POST['name'] = '';
        $_POST['username'] = '';
        $_POST['password'] = '';
        $_POST['role'] = 'teacher';

        // Include the script and execute
        ob_start();
        include 'src/register.php'; // Update this path to the actual location of your script
        $output = ob_get_clean();

        $this->assertStringContainsString('All fields are required.', $output);
    }
}

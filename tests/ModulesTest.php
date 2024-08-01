<?php
require_once __DIR__ . '/../src/modules.php'; // Adjust the path as necessary
use PHPUnit\Framework\TestCase;

class ModulesTest extends TestCase
{
    public function testFetchStudentModules()
    {
        // Create a mock for the mysqli class
        $conn = $this->createMock(mysqli::class);

        // Create a mock for the prepared statement
        $stmt = $this->createMock(mysqli_stmt::class);
        $result = $this->createMock(mysqli_result::class);

        // Set up the expected result
        $expectedModules = [
            2023 => [
                1 => [
                    [
                        'module_id' => 1,
                        'module_name' => 'Module 1',
                        'module_code' => 'M1',
                        'year' => 2023,
                        'semester' => 1,
                        'grade' => 'A',
                        'mid_marks' => 85
                    ]
                ]
            ]
        ];

        // Configure the mock statement
        $stmt->expects($this->once())
            ->method('bind_param')
            ->with("i", 1);

        $stmt->expects($this->once())
            ->method('execute');

        $stmt->expects($this->once())
            ->method('get_result')
            ->willReturn($result);

        // Configure the mock result
        $result->expects($this->exactly(2))
            ->method('fetch_assoc')
            ->willReturnOnConsecutiveCalls(
                [
                    'module_id' => 1,
                    'module_name' => 'Module 1',
                    'module_code' => 'M1',
                    'year' => 2023,
                    'semester' => 1,
                    'grade' => 'A',
                    'mid_marks' => 85
                ],
                false
            );

        $conn->expects($this->once())
            ->method('prepare')
            ->with($this->anything())
            ->willReturn($stmt);

        // Call the function with the mock connection
        $modules = fetchStudentModules($conn, 1, 2023, 1);

        // Assert that the result matches the expected result
        $this->assertEquals($expectedModules, $modules);
    }
}

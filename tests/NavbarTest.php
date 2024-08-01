<?php
use PHPUnit\Framework\TestCase;

class NavbarTest extends TestCase
{
    protected function setUp(): void
    {
        // Start a session for testing
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function tearDown(): void
    {
        // End the session after the test
        session_destroy();
    }

    private function getNavbarHtml($role)
    {
        // Set the session role
        $_SESSION['role'] = $role;

        // Capture output
        ob_start();
        require __DIR__ . '/../path/to/navbar.php'; // Adjust the path
        $output = ob_get_clean();

        return $output;
    }

    public function testTeacherNavbar()
    {
        $output = $this->getNavbarHtml('teacher');

        // Assert that teacher-specific links are present
        $this->assertStringContainsString('<a href="teacher_dashboard.php" class="active">Dashboard</a>', $output);
        $this->assertStringContainsString('<a href="student_list.php">Student List</a>', $output);
        $this->assertStringContainsString('<a href="exam_results.php">Exam Results</a>', $output);
        $this->assertStringContainsString('<a href="degrees.php">Degrees</a>', $output);

        // Assert that student-specific links are not present
        $this->assertStringNotContainsString('<a href="student_dashboard.php" class="active">Dashboard</a>', $output);
        $this->assertStringNotContainsString('<a href="view_results.php">View Results</a>', $output);
    }

    public function testStudentNavbar()
    {
        $output = $this->getNavbarHtml('student');

        // Assert that student-specific links are present
        $this->assertStringContainsString('<a href="student_dashboard.php" class="active">Dashboard</a>', $output);
        $this->assertStringContainsString('<a href="view_results.php">View Results</a>', $output);

        // Assert that teacher-specific links are not present
        $this->assertStringNotContainsString('<a href="teacher_dashboard.php" class="active">Dashboard</a>', $output);
        $this->assertStringNotContainsString('<a href="student_list.php">Student List</a>', $output);
        $this->assertStringNotContainsString('<a href="exam_results.php">Exam Results</a>', $output);
        $this->assertStringNotContainsString('<a href="degrees.php">Degrees</a>', $output);
    }

    public function testNoRoleNavbar()
    {
        // Simulate no session role set
        unset($_SESSION['role']);

        $output = $this->getNavbarHtml(null);

        // Assert that no specific role links are present
        $this->assertStringNotContainsString('<a href="teacher_dashboard.php" class="active">Dashboard</a>', $output);
        $this->assertStringNotContainsString('<a href="student_list.php">Student List</a>', $output);
        $this->assertStringNotContainsString('<a href="exam_results.php">Exam Results</a>', $output);
        $this->assertStringNotContainsString('<a href="degrees.php">Degrees</a>', $output);
        $this->assertStringNotContainsString('<a href="student_dashboard.php" class="active">Dashboard</a>', $output);
        $this->assertStringNotContainsString('<a href="view_results.php">View Results</a>', $output);
    }
}

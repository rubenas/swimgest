<?php

use PHPUnit\Framework\TestCase;

require_once './controller/SwimmerController.php';

/**
 * Class SwimmerControllerTest
 *
 * Unit tests for SwimmerController class, validating profile management operations 
 * such as updating emails and passwords with various inputs.
 */
class SwimmerControllerTest extends TestCase
{
    /**
     * Sets up mock data for testing and initializes session data.
     *
     * This method initializes POST, FILES, and SESSION variables to simulate
     * user input and session data needed for the test cases.
     */
    protected function setUp(): void
    {
        $_POST = [];
        $_FILES = [];
        $_SESSION = ['user_id' => 1]; // Simulated user session
    }

    /**
     * Test for updating swimmer email with a unique email.
     *
     * Validates that a new unique email is successfully updated, returning 
     * a success message.
     */
    public function testUpdateEmailValid()
    {
        $_POST['email'] = 'newemail@example.com';
        $controller = new SwimmerController();
        $result = $controller->updateEmail();

        $this->assertTrue($result['success']);
        $this->assertEquals('Email actualizado correctamente', $result['msg']);
    }

    /**
     * Test for updating swimmer password with valid criteria.
     *
     * Ensures that a password meeting all criteria updates successfully and
     * returns a success message.
     */
    public function testUpdatePasswordValid()
    {
        $_POST['password'] = 'ValidPass123';
        $_POST['password2'] = 'ValidPass123';

        $controller = new SwimmerController();
        $result = $controller->updatePassword();

        $this->assertTrue($result['success']);
        $this->assertEquals('Contraseña actualizada correctamente', $result['msg']);
    }

    /**
     * Test for updating swimmer password with mismatched passwords.
     *
     * Verifies that when passwords do not match, an error is returned 
     * indicating the mismatch.
     */
    public function testUpdatePasswordMismatch()
    {
        $_POST['password'] = 'ValidPass123';
        $_POST['password2'] = 'MismatchPass123';

        $controller = new SwimmerController();
        $result = $controller->updatePassword();

        $this->assertFalse($result['success']);
        $this->assertEquals('Las contraseñas no coinciden', $result['error']);
    }

    /**
     * Test for updating swimmer password with insufficient length.
     *
     * Confirms that a password shorter than 8 characters triggers an error
     * for minimum length requirements.
     */
    public function testUpdatePasswordShortLength()
    {
        $_POST['password'] = 'Short1';
        $_POST['password2'] = 'Short1';

        $controller = new SwimmerController();
        $result = $controller->updatePassword();

        $this->assertFalse($result['success']);
        $this->assertEquals('La contraseña debe tener un mínimo de 8 caracteres', $result['error']);
    }

    /**
     * Test for updating swimmer password without required character types.
     *
     * Checks that a password lacking uppercase, lowercase, or numeric characters
     * returns an error indicating the missing requirements.
     */
    public function testUpdatePasswordMissingCharacterTypes()
    {
        $_POST['password'] = 'nouppercase123';
        $_POST['password2'] = 'nouppercase123';

        $controller = new SwimmerController();
        $result = $controller->updatePassword();

        $this->assertFalse($result['success']);
        $this->assertEquals('La contraseña debe contener al menos una letra mayúscula, una minúscula y un número', $result['error']);
    }
}

<?php

use PHPUnit\Framework\TestCase;

require_once './controller/adminCompetitionController.php';

/**
 * Class AdminCompetitionControllerTest
 *
 * This test class verifies the behavior of the AdminCompetitionController's 
 * functions, particularly in terms of competition creation and validation. 
 * Tests focus on ensuring valid and invalid inputs are handled appropriately.
 */
class AdminCompetitionControllerTest extends TestCase
{
    /**
     * Tests the list() function in AdminCompetitionController.
     *
     * This function ensures that the list of competitions is returned correctly 
     * and that the result is successful.
     *
     * @return void
     */
    public function testList()
    {
        $controller = new AdminCompetitionController();

        // Execute the list() function
        $result = $controller->list();

        // Verify that the result indicates success
        $this->assertTrue($result['success']);

        // Verify that it returns an array of competitions
        $this->assertIsArray($result['object']);
    }

    /**
     * Sets up test environment with simulated POST and FILE data.
     *
     * This setup function simulates a POST request with competition data and 
     * sets a simulated file upload to test cases where no image is uploaded.
     *
     * @return void
     */
    protected function setUp(): void
    {
        // Simulate POST data
        $_POST = [
            'name' => 'Competición Test',
            'place' => 'Lugar Test',
            'inscriptionsLimit' => 100,
            'startDate' => '2024-12-01',
            'endDate' => '2024-12-10',
            'inscriptionsDeadLine' => '2024-11-25',
        ];

        $_FILES = [
            'picture' => [
                'size' => 0 // Simulate no image uploaded
            ]
        ];
    }

    /**
     * Tests fromPost() function with valid data.
     *
     * This function verifies that the competition is successfully created and 
     * that the response contains a valid Competition object.
     *
     * @return void
     */
    public function testFromPostValidData()
    {
        $result = AdminCompetitionController::fromPost();

        // Verify that the result indicates success
        $this->assertTrue($result['success']);

        // Verify that a competition object was created
        $this->assertInstanceOf(Competition::class, $result['object']);
    }

    /**
     * Tests fromPost() function with an invalid end date.
     *
     * This function ensures that when the end date is earlier than the start 
     * date, the operation fails and returns an appropriate error message.
     *
     * @return void
     */
    public function testFromPostInvalidEndDate()
    {
        $_POST['endDate'] = '2024-11-20'; // End date earlier than start date

        $result = AdminCompetitionController::fromPost();

        // Verify that the result indicates failure
        $this->assertFalse($result['success']);

        // Verify that the appropriate error message is returned
        $this->assertEquals('La fecha de inicio debe ser anterior a la de fin', $result['error']);
    }

    /**
     * Tests fromPost() function with an invalid inscription deadline.
     *
     * This function ensures that when the inscription deadline is later than the 
     * start date, the operation fails and returns an appropriate error message.
     *
     * @return void
     */
    public function testFromPostInvalidDeadline()
    {
        $_POST['inscriptionsDeadLine'] = '2024-12-05'; // Deadline after start date

        $result = AdminCompetitionController::fromPost();

        // Verify that the result indicates failure
        $this->assertFalse($result['success']);

        // Verify that the appropriate error message is returned
        $this->assertEquals('La fecha de cierre de inscripciones debe ser anterior a la de inicio', $result['error']);
    }
}

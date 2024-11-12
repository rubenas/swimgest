<?php
use PHPUnit\Framework\TestCase;

require_once './model/Basemodel.php';
require_once './model/Competition.php';
require_once './model/Email.php';
require_once './utils/makeEmailBody.php';

class EmailIntegrationTest extends TestCase
{
    public function testCompetitionEmailGeneration()
    {
        error_reporting(E_ALL);
        // Making a competition object
        $competition = new Competition();
        $competition->setName('Competición de prueba');
        $competition->setPlace('CIFP A Carballeira');
        $competition->setDescription('Esto es una prueba');
        $competition->setStartDate('2024-11-18');
        $competition->setEndDate('2024-11-20');
        $competition->setDeadLine('2024-11-10');
        $competition->setInscriptionsLimit(2);

        // Making an email template
        $emailTemplate = new Email();
        $emailTemplate->setSubject('Plazo de inscripción abierto para [name]');
        $emailTemplate->setBody('Apúntate ya a [name]');

        // Generating mail
        $result = makeEmail($emailTemplate->getSubject(), $emailTemplate->getBody(), $competition);

        // Comprobaciones de integración
        $this->assertEquals('Plazo de inscripción abierto para Competición de prueba', $result['subject']);
        $this->assertStringContainsString('Apúntate ya a Competición de prueba', $result['body']);
    }
}

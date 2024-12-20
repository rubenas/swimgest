<?php 

require_once './utils/functions.php';

/**
 * Generates an email based on the type of object provided.
 *
 * This function determines the type of object and calls the appropriate email generation function
 * based on the class of the object (`Competition`, `Event`, or `Questionary`).
 *
 * @param string $subject The subject of the email template.
 * @param string $body The body content of the email template.
 * @param object $object The object (instance of `Competition`, `Event`, or `Questionary`) for which to generate the email.
 * @return array An associative array containing the 'subject' and 'body' of the email.
 */

function makeEmail($subject, $body, $object)
{
    switch (get_class($object)) {
        case 'Competition':
            return makeCompetitionEmail($subject, $body, $object);

        case 'Event':
            return makeEventEmail($subject, $body, $object);

        case 'Questionary':
            return makeQuestionaryEmail($subject, $body, $object);
    }
}

/**
 * Generates an email for a competition, replacing placeholders with actual competition data.
 *
 * @param string $subject The subject of the email template with placeholders.
 * @param string $body The body content of the email template with placeholders.
 * @param object $competition An instance of the `Competition` class containing data to replace placeholders.
 * @return array An associative array containing the 'subject' and 'body' of the generated email.
 */

function makeCompetitionEmail($subject, $body, $competition)
{
    $subject = str_replace('[name]', $competition->getName()  ?? '', $subject);
    $subject = str_replace('[place]', $competition->getPlace() ?? '', $subject);
    $subject = str_replace('[location]', $competition->getLocation() ?? '', $subject);
    $subject = str_replace('[description]', $competition->getDescription() ?? '', $subject);
    $subject = str_replace('[startDate]', formatDMYDate($competition->getStartDate()) ?? '', $subject);
    $subject = str_replace('[endDate]', formatDMYDate($competition->getEndDate()) ?? '', $subject);
    $subject = str_replace('[deadLine]', formatDMYHMDate($competition->getDeadLine()) ?? '', $subject);
    $subject = str_replace('[inscriptionsLimit]', $competition->getInscriptionsLimit() ?? '', $subject);

    $body = str_replace('[name]', $competition->getName() ?? '', $body);
    $body = str_replace('[place]', $competition->getPlace() ?? '', $body);
    $body = str_replace('[location]', $competition->getLocation() ?? '', $body);
    $body = str_replace('[description]', $competition->getDescription() ?? '', $body);
    $body = str_replace('[startDate]', formatDMYDate($competition->getStartDate()) ?? '', $body);
    $body = str_replace('[endDate]', formatDMYDate($competition->getEndDate()) ?? '', $body);
    $body = str_replace('[deadLine]', formatDMYHMDate($competition->getDeadLine()) ?? '', $body);
    $body = str_replace('[inscriptionsLimit]', $competition->getInscriptionsLimit() ?? '', $body);

    require './utils/config.php'; 
    /**
     * @var string $logoRoute
     */
    $logo = '<img src="'.$logoRoute.'" style="width:150px">';
    $logoRoute = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://'.$_SERVER['SERVER_NAME'].str_replace('index.php','',$_SERVER['PHP_SELF']).str_replace('./','',$logoRoute);
    $logo = '<img src="'.$logoRoute.'" width="150px">';
    $body = str_replace('[logo]', $logo, $body);

    return [
        'subject' => $subject,
        'body' => $body
    ];
}

/**
 * Generates an email for an event, replacing placeholders with actual event data.
 *
 * @param string $subject The subject of the email template with placeholders.
 * @param string $body The body content of the email template with placeholders.
 * @param object $event An instance of the `Event` class containing data to replace placeholders.
 * @return array An associative array containing the 'subject' and 'body' of the generated email.
 */

function makeEventEmail($subject, $body, $event)
{
    $subject = str_replace('[name]', $event->getName(), $subject);
    $subject = str_replace('[place]', $event->getPlace(), $subject);
    $subject = str_replace('[location]', $event->getLocation(), $subject);
    $subject = str_replace('[description]', $event->getDescription(), $subject);
    $subject = str_replace('[startDate]', formatDMYDate($event->getStartDate()), $subject);
    $subject = str_replace('[endDate]', formatDMYDate($event->getEndDate()), $subject);
    $subject = str_replace('[deadLine]', formatDMYHMDate($event->getDeadLine()), $subject);

    $body = str_replace('[name]', $event->getName(), $body);
    $body = str_replace('[place]', $event->getPlace(), $body);
    $body = str_replace('[location]', $event->getLocation(), $body);
    $body = str_replace('[description]', $event->getDescription(), $body);
    $body = str_replace('[startDate]', formatDMYDate($event->getStartDate()), $body);
    $body = str_replace('[endDate]', formatDMYDate($event->getEndDate()), $body);
    $body = str_replace('[deadLine]', formatDMYHMDate($event->getDeadLine()), $body);

    require './utils/config.php'; 
    /**
     * @var string $logoRoute
     */
    $logo = '<img src="'.$logoRoute.'" style="width:150px">';
    $logoRoute = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://'.$_SERVER['SERVER_NAME'].str_replace('index.php','',$_SERVER['PHP_SELF']).str_replace('./','',$logoRoute);
    $logo = '<img src="'.$logoRoute.'" width="150px">';
    $body = str_replace('[logo]', $logo, $body);

    return [
        'subject' => $subject,
        'body' => $body
    ];
}

/**
 * Generates an email for a questionary, replacing placeholders with actual questionary data.
 *
 * @param string $subject The subject of the email template with placeholders.
 * @param string $body The body content of the email template with placeholders.
 * @param object $questionary An instance of the `Questionary` class containing data to replace placeholders.
 * @return array An associative array containing the 'subject' and 'body' of the generated email.
 */

function makeQuestionaryEmail($subject, $body, $questionary)
{
    $subject = str_replace('[name]', $questionary->getName(), $subject);
    $subject = str_replace('[description]', $questionary->getDescription(), $subject);
    $subject = str_replace('[deadLine]', formatDMYHMDate($questionary->getDeadLine()), $subject);

    $body = str_replace('[name]', $questionary->getName(), $body);
    $body = str_replace('[description]', $questionary->getDescription(), $body);
    $body = str_replace('[deadLine]', formatDMYHMDate($questionary->getDeadLine()), $body);

    require './utils/config.php'; 
    /**
     * @var string $logoRoute
     */
    $logo = '<img src="'.$logoRoute.'" style="width:150px">';
    $logoRoute = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://'.$_SERVER['SERVER_NAME'].str_replace('index.php','',$_SERVER['PHP_SELF']).str_replace('./','',$logoRoute);
    $logo = '<img src="'.$logoRoute.'" width="150px">';
    $body = str_replace('[logo]', $logo, $body);

    return [
        'subject' => $subject,
        'body' => $body
    ];
}

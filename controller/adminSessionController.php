<?php

require_once './controller/baseController.php';

/**
 * AdminSessionController handles the management of sessions within a competition.
 * It provides functionalities to create, update, delete, and organize sessions, as well as manage their related races.
 * The class interacts with various session and competition-related models to maintain the data integrity and structure.
 */

class AdminSessionController extends BaseController
{

    /**
     * Creates a Session object from POST form data.
     * 
     * @return array Associative array with 'success' status and the created Session object or validation errors.
     */

    public static function fromPost()
    {
        $validation = self::checkRequiredFields(array('journeyId', 'name', 'time', 'inscriptionsLimit'));

        if (!$validation['success']) {
            return $validation;
        }

        $session = new Session();
        $session->setName($_POST['name']);
        $session->setJourneyId($_POST['journeyId']);
        $session->setInscriptionsLimit($_POST['inscriptionsLimit']);
        $session->setTime($_POST['time']);

        return [
            "success" => true,
            "object" => $session
        ];
    }

    /**
     * Adds a session to the database.
     * 
     * @return mixed Returns the result of filling the competition from the session.
     */

    public function add()
    {
        $this->view = 'admin/competition/details';

        $session = self::fromPost();

        Session::add($session['object']);

        return $this->fillCompetitionFromSession($session['object']);
    }

    /**
     * Adds a session to the database and returns a partial view on an AJAX request.
     * 
     * @return mixed Returns the result of filling the journey from the session.
     */

    public function ajaxAdd()
    {
        $session = self::fromPost();

        Session::add($session['object']);

        $this->view = 'admin/journey/details';

        return Journey::fill($session['object']->getJourneyId());
    }

    /**
     * Shows the remove confirmation window for a session.
     * 
     * @param int $id The ID of the session to confirm removal.
     * 
     * @return mixed Returns the session and journey name if found, or a not found error.
     */

    public function removeConfirm($id)
    {
        $this->view = 'admin/session/remove';

        /** @var Session $session */
        $session = Session::getById($id);

        if (!$session) return $this->notFoundError;

        /** @var Journey $journey */
        $journey = Journey::getById($session->getJourneyId());

        if (!$journey) return $this->notFoundError;

        return [
            'object' => $session,
            'journeyName' => $journey->getName()
        ];
    }

    /**
     * Removes a session from the database.
     * 
     * @param int $id The ID of the session to remove.
     * 
     * @return mixed Returns the result of filling the competition from the session or a not found error.
     */

    public function remove($id)
    {
        $session = Session::getById($id);

        if (!$session) return $this->notFoundError;

        Session::remove($id);

        $this->view = 'admin/competition/details';

        return $this->fillCompetitionFromSession($session);
    }

    /**
     * Removes a session from the database and returns a partial view on an AJAX request.
     * 
     * @param int $id The ID of the session to remove.
     * 
     * @return mixed Returns the result of filling the journey from the session or a not found error.
     */

    public function ajaxRemove($id)
    {
        /** @var Session $session */
        $session = Session::getById($id);

        if (!$session) return $this->notFoundError;

        Session::remove($id);

        $this->view = 'admin/journey/details';

        return Journey::fill($session->getJourneyId());
    }

    /**
     * Shows the edit session window.
     * 
     * @param int $id The ID of the session to edit.
     * 
     * @return array Returns the session object if found, or a not found error.
     */

    public function edit($id)
    {
        $session = Session::getById($id);

        if (!$session) return $this->notFoundError;

        $this->view = "admin/session/edit";

        return [
            'success' => true,
            'object' => $session
        ];
    }

    /**
     * Updates a session using POST data.
     * 
     * @param int $id The ID of the session to update.
     * 
     * @return mixed Returns the result of filling the competition from the session or validation errors.
     */

    public function update($id)
    {
        $this->view = 'admin/competition/details';

        $validation = self::checkRequiredFields(array('name', 'time', 'inscriptionsLimit'));

        if (!$validation['success']) return $validation;

        /** @var Session $session */
        $session = Session::getById($id);

        if (!$session) return $this->notFoundError;

        $columns = [
            'name' => $_POST['name'],
            'inscriptionsLimit' => $_POST['inscriptionsLimit'],
            'time' => $_POST['time']
        ];

        if (!Session::updateFromId($columns, $id)) return $this->notFoundError;

        return $this->fillCompetitionFromSession($session);
    }

    /**
     * Updates a session using POST data and returns a partial view on an AJAX request.
     * 
     * @param int $id The ID of the session to update.
     * 
     * @return array Returns the updated session object if successful, or a not found error.
     */

    public function ajaxUpdate($id)
    {
        $result = $this->update($id);

        if (!$result['success']) return $result;

        $this->view = 'admin/session/details';

        return [
            'success' => true,
            'object' => Session::fill($id)
        ];
    }

    /**
     * Loads the view to add a race to the session.
     * 
     * @param int $id The ID of the session to add a race to.
     * 
     * @return array Returns the session object if found, or a not found error.
     */

    public function addRace($id)
    {
        /** @var Session $session */
        $session = Session::getById($id);

        if (!$session) return $this->notFoundError;

        $this->view = 'admin/race/add';

        return [
            'success' => true,
            'object' => $session
        ];
    }

    /**
     * Fills a competition with all sessions and journeys related to the session.
     * 
     * @param Session $session The session object used to fill the competition.
     * 
     * @return mixed Returns the filled competition object, or a not found error.
     */

    public function fillCompetitionFromSession($session)
    {
        /** @var Journey $journey */
        $journey = Journey::getById($session->getJourneyId());

        if (!$journey) return $this->notFoundError;

        return Competition::fill($journey->getCompetitionId());
    }
}

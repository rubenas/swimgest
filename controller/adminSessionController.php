<?php

require_once './controller/baseController.php';

class AdminSessionController extends BaseController
{

    /*Create a Session Object from Post form*/

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

    /*Add session to DB*/

    public function add()
    {
        $this->view = 'admin/competition/details';

        $session = self::fromPost();

        Session::add($session['object']);

        return $this->fillCompetitionFromSession($session['object']);
    }

    /*Add session to DB and return partial view on ajax request*/

    public function ajaxAdd()
    {
        $session = self::fromPost();

        Session::add($session['object']);

        $this->view = 'admin/journey/details';

        return Journey::fill($session['object']->getJourneyId());
    }

    /* Show remove confirmation window */

    public function removeConfirm($id)
    {
        $this->view = 'admin/session/remove';

        /**@var Session $session */
        $session = Session::getById($id);

        if (!$session) return $this->notFoundError;

        /**@var Journey $journey */
        $journey = Journey::getById($session->getJourneyId());

        if (!$journey) return $this->notFoundError;

        return [
            'object' => $session,
            'journeyName' => $journey->getName()
        ];
    }

    /*Remove session from DB */

    public function remove($id)
    {
        $session = Session::getById($id);

        if (!$session) return $this->notFoundError;

        Session::remove($id);

        $this->view = 'admin/competition/details';

        return $this->fillCompetitionFromSession($session);
    }

    /*Remove from DB and return partial view on ajax request */

    public function ajaxRemove($id)
    {
        /**@var Session $session */
        $session = Session::getById($id);

        if (!$session) return $this->notFoundError;

        Session::remove($id);

        $this->view = 'admin/journey/details';

        return Journey::fill($session->getJourneyId());
    }

    /**Show edit session window */

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

    /*Update session from POST*/
    public function update($id)
    {
        $this->view = 'admin/competition/details';

        $validation = self::checkRequiredFields(array('name', 'time', 'inscriptionsLimit'));

        if (!$validation['success']) return $validation;

        /**@var Session $session */
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

    /*Update from post and return partial view on ajax request */

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

    /**Load view Add race to session */
    public function addRace($id)
    {
        /**@var Session $session */
        $session = Session::getById($id);

        if (!$session) return $this->notFoundError;

        $this->view = 'admin/race/add';

        return [
            'success' => true,
            'object' => $session
        ];
    }

    /* Fills a competition with all sessions and journeys from session */

    public function fillCompetitionFromSession($session)
    {
        /**@var Journey $journey */
        $journey = Journey::getById($session->getJourneyId());

        if (!$journey) return $this->notFoundError;

        return Competition::fill($journey->getCompetitionId());
    }
}

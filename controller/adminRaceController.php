<?php

require_once './controller/baseController.php';

class AdminRaceController extends BaseController
{

    /*Create a Race Object from Post form*/

    public static function fromPost()
    {

        $validation = self::checkRequiredFields(array('sessionId', 'style', 'distance', 'gender', 'number'));

        if (!$validation['success']) {

            return $validation;
        }

        $race = new Race();

        $race->setSessionId($_POST['sessionId']);
        $race->setStyle($_POST['style']);
        $race->setDistance($_POST['distance']);
        $race->setGender($_POST['gender']);
        $race->setNumber($_POST['number']);
        $race->setIsRelay(str_contains($_POST['distance'], '4x'));

        return [
            "success" => true,
            "object" => $race
        ];
    }

    /*Add race to DB*/

    public function add()
    {
        $this->view = 'admin/competition/details';

        $race = self::fromPost();

        Race::add($race['object']);

        return $this->fillCompetitionFromRace($race['object']);
    }

    /*Add race to DB and return partial view on ajax request*/

    public function ajaxAdd()
    {
        $race = self::fromPost();

        Race::add($race['object']);

        $this->view = 'admin/session/details';

        return Session::fill($race['object']->getSessionId());
    }

    /* Show remove confirmation window */

    public function removeConfirm($id)
    {
        $this->view = 'admin/race/remove';

        /**@var Race $race */
        $race = Race::getById($id);

        if (!$race) return $this->notFoundError;

        /**@var Session $session */
        $session = Session::getById($race->getSessionId());

        if (!$session) return $this->notFoundError;

        return [
            'sucess' => true,
            'object' => $race,
            'sessionName' => $session->getName()
        ];
    }

    /*Remove race from DB */

    public function remove($id)
    {
        /**@var Race $race */
        $race = Race::getById($id);

        if (!$race) return $this->notFoundError;

        Race::remove($id);

        $this->resetNumbers($race);

        $this->view = 'admin/competition/details';

        return $this->fillCompetitionFromRace($race);
    }

    /*Remove from DB and return partial view on ajax request */

    public function ajaxRemove($id)
    {
        /**@var Race $race */
        $race = Race::getById($id);

        if (!$race) return $this->notFoundError;

        Race::remove($id);

        $this->resetNumbers($race);

        $this->view = 'admin/session/details';

        return Session::fill($race->getSessionId());
    }

    /**Move up a race if it's possible */

    public function moveUp($id)
    {
        /**@var Race $race */
        $race = Race::getById($id);

        if (!$race) return $this->notFoundError;

        $sessionId = $race->getSessionId();

        if ($race->getNumber() > 0) {

            $previousNumber = $race->getNumber() - 1;
            $previousRace = Race::getAll(["number = $previousNumber AND sessionID = $sessionId"], [])[0];

            $previousRace->setNumber($race->getNumber());
            $race->setNumber($previousNumber);

            Race::updateNumber($previousRace);
            Race::updateNumber($race);
        }

        $this->view = 'admin/competition/details';

        return $this->fillCompetitionFromRace($race);
    }

    /*Move up a race if it's possible on ajax request */

    public function ajaxMoveUp($id)
    {
        /**@var Race $race */
        $race = Race::getById($id);

        if (!$race) return $this->notFoundError;

        $sessionId = $race->getSessionId();

        if ($race->getNumber() > 0) {

            $previousNumber = $race->getNumber() - 1;
            $previousRace = Race::getAll(["number = $previousNumber AND sessionID = $sessionId"], [])[0];

            $previousRace->setNumber($race->getNumber());
            $race->setNumber($previousNumber);

            Race::updateNumber($previousRace);
            Race::updateNumber($race);
        }

        $this->view = 'admin/session/details';

        return Session::fill($sessionId);
    }

    /**Move up a race if it's possible */
    public function moveDown($id)
    {
        /**@var Race $race */
        $race = Race::getById($id);

        if (!$race) return $this->notFoundError;

        $sessionId = $race->getSessionId();

        /**@var Session $session */
        $session = Session::getById($sessionId);

        if ($race->getNumber() < $session->getNumRaces() - 1) {

            $nextNumber = $race->getNumber() + 1;
            $nextRace = Race::getAll(["number = $nextNumber AND sessionID = $sessionId"], [])[0];

            $nextRace->setNumber($race->getNumber());
            $race->setNumber($nextNumber);

            Race::updateNumber($nextRace);
            Race::updateNumber($race);
        }

        $this->view = 'admin/competition/details';

        return $this->fillCompetitionFromRace($race);
    }

    /*Move up a race if it's possible on ajax request */

    public function ajaxMoveDown($id)
    {
        /**@var Race $race */
        $race = Race::getById($id);

        if (!$race) return $this->notFoundError;

        $sessionId = $race->getSessionId();

        /**@var Session $session */
        $session = Session::getById($sessionId);

        if ($race->getNumber() < $session->getNumRaces() - 1) {

            $nextNumber = $race->getNumber() + 1;
            $nextRace = Race::getAll(["number = $nextNumber AND sessionID = $sessionId"], [])[0];

            $nextRace->setNumber($race->getNumber());
            $race->setNumber($nextNumber);

            Race::updateNumber($nextRace);
            Race::updateNumber($race);
        }
        $this->view = 'admin/session/details';

        return Session::fill($sessionId);
    }

    /**Update numbers from races when we remove one */

    public function resetNumbers($race)
    {
        $races = Race::getAll(['sessionId = ' . $race->getSessionId()], ['number']);

        $count = 0;

        foreach ($races as $race) {

            $race->setNumber($count);

            $count++;

            Race::updateNumber($race);
        }
    }

    /* Fills a competition with all sessions and journeys from race */

    public function fillCompetitionFromRace($race)
    {
        /**@var Session $session */
        $session = Session::getById($race->getSessionId());
        
        /**@var Journey $journey */
        $journey = Journey::getById($session->getJourneyId());

        if (!$journey) return $this->notFoundError;

        return Competition::fill($journey->getCompetitionId());
    }
}

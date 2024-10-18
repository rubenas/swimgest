<?php

require_once './controller/baseController.php';

/**
 * AdminRaceController
 * 
 * This class handles the management of races in the admin panel.
 * It provides functionalities to add, edit, remove, and rearrange races.
 */

class AdminRaceController extends BaseController
{

    /**
     * Create a Race object from POST form data
     * 
     * @return array Returns an array with the validation result and the Race object.
     */

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

    /**
     * Add a new race to the database
     * 
     * @return array Returns the competition filled with the race data.
     */
    
    public function add()
    {
        $this->view = 'admin/competition/details';
        $race = self::fromPost();
        Race::add($race['object']);
        return $this->fillCompetitionFromRace($race['object']);
    }

    /**
     * Add a race and return a partial view for AJAX requests
     * 
     * @return array Returns the session filled with the race data.
     */

    public function ajaxAdd()
    {
        $race = self::fromPost();
        Race::add($race['object']);
        $this->view = 'admin/session/details';
        return Session::fill($race['object']->getSessionId());
    }

    /**
     * Show confirmation window for race removal
     * 
     * @param int $id The ID of the race to be removed.
     * @return array Returns the race data along with session information.
     */

    public function removeConfirm($id)
    {
        $this->view = 'admin/race/remove';

        /** @var Race $race */
        $race = Race::getById($id);

        if (!$race) return $this->notFoundError;

        /** @var Session $session */
        $session = Session::getById($race->getSessionId());

        if (!$session) return $this->notFoundError;

        return [
            'sucess' => true,
            'object' => $race,
            'sessionName' => $session->getName()
        ];
    }

    /**
     * Remove a race from the database
     * 
     * @param int $id The ID of the race to be removed.
     * @return array Returns the competition filled with updated race data.
     */
    
    public function remove($id)
    {
        /** @var Race $race */
        $race = Race::getById($id);

        if (!$race) return $this->notFoundError;

        Race::remove($id);
        $this->resetNumbers($race);
        $this->view = 'admin/competition/details';
        return $this->fillCompetitionFromRace($race);
    }

    /**
     * Remove a race and return a partial view for AJAX requests
     * 
     * @param int $id The ID of the race to be removed.
     * @return array Returns the session filled with updated race data.
     */

    public function ajaxRemove($id)
    {
        /** @var Race $race */
        $race = Race::getById($id);

        if (!$race) return $this->notFoundError;

        Race::remove($id);
        $this->resetNumbers($race);
        $this->view = 'admin/session/details';
        return Session::fill($race->getSessionId());
    }

    /**
     * Move a race up in the race order
     * 
     * @param int $id The ID of the race to move up.
     * @return array Returns the competition filled with the updated race order.
     */

    public function moveUp($id)
    {
        /** @var Race $race */
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

    /**
     * Move a race up and return a partial view for AJAX requests
     * 
     * @param int $id The ID of the race to move up.
     * @return array Returns the session filled with updated race order.
     */

    public function ajaxMoveUp($id)
    {
        /** @var Race $race */
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

    /**
     * Move a race down in the race order
     * 
     * @param int $id The ID of the race to move down.
     * @return array Returns the competition filled with the updated race order.
     */

    public function moveDown($id)
    {
        /** @var Race $race */
        $race = Race::getById($id);

        if (!$race) return $this->notFoundError;

        $sessionId = $race->getSessionId();

        /** @var Session $session */
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

    /**
     * Move a race down and return a partial view for AJAX requests
     * 
     * @param int $id The ID of the race to move down.
     * @return array Returns the session filled with updated race order.
     */

    public function ajaxMoveDown($id)
    {
        /** @var Race $race */
        $race = Race::getById($id);

        if (!$race) return $this->notFoundError;

        $sessionId = $race->getSessionId();

        /** @var Session $session */
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

    /**
     * Update the race order after one is removed
     * 
     * @param Race $race The removed race object.
     * @return void
     */

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

    /**
     * Fills a competition with all sessions and journeys from the race
     * 
     * @param Race $race The race object.
     * @return array Returns the competition filled with its sessions and journeys.
     */

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

<?php

require_once './controller/baseController.php';

/**
 * AdminJourneyController class
 *
 * This controller manages journeys within the admin panel of the application.
 * It provides functionalities for creating, updating, removing, and viewing journeys
 * related to competitions. The controller validates user input, ensures that journey
 * dates fall within the competition's date range, and handles interactions with the 
 * database for journey management.
 */

class AdminJourneyController extends BaseController
{

    /**
     * Create a Journey Object from Post form
     *
     * This method validates the required fields and creates a Journey object
     * from the submitted form data. It also checks if the journey date is within
     * the competition's date range.
     *
     * @return array An array containing the success status and the Journey object or an error message.
     */

    public static function fromPost()
    {
        $validation = self::checkRequiredFields(array('competitionId', 'name', 'date', 'inscriptionsLimit'));

        if (!$validation['success']) {
            return $validation;
        }

        $journey = new Journey();

        $journey->setName($_POST['name']);
        $journey->setCompetitionId($_POST['competitionId']);
        $journey->setInscriptionsLimit($_POST['inscriptionsLimit']);
        $journey->setDate($_POST['date']);

        /** @var Competition $competition */
        $competition = Competition::getById($journey->getCompetitionId());

        if ($journey->getDate() > $competition->getEndDate() || $journey->getDate() < $competition->getStartDate()) {
            return [
                'success' => false,
                'error' => 'La fecha de esta jornada está fuera del rango de fechas de la competición',
                "object" => $journey
            ];
        }

        return [
            "success" => true,
            "object" => $journey
        ];
    }

    /**
     * Add journey to DB
     *
     * This method adds a new journey to the database and redirects to the
     * competition details view.
     *
     * @return Competition An instance of the Competition object after adding the journey.
     */

    public function add()
    {
        $this->view = 'admin/competition/details';

        $journey = self::fromPost();

        Journey::add($journey['object']);

        return Competition::fill($journey['object']->getCompetitionId());
    }

    /**
     * Show remove confirmation window
     *
     * This method retrieves a journey by its ID and prepares the view for 
     * removal confirmation.
     *
     * @param int $id The ID of the journey to be removed.
     * @return array An array containing the success status and the Journey object or an error message.
     */
    
    public function removeConfirm($id)
    {
        $this->view = 'admin/journey/remove';

        $journey = Journey::getById($id);

        if (!$journey) return $this->notFoundError;

        return [
            'success' => true,
            'object' => $journey
        ];
    }

    /**
     * Remove journey from DB
     *
     * This method removes a journey from the database based on the given ID.
     *
     * @param int $id The ID of the journey to be removed.
     * @return Competition An instance of the Competition object after removing the journey.
     */

    public function remove($id)
    {
        /** @var Journey $journey */
        $journey = Journey::getById($id);

        if (!$journey) return $this->notFoundError;

        Journey::remove($id);

        $this->view = 'admin/competition/details';

        return Competition::fill($journey->getCompetitionId());
    }

    /**
     * Show edit journey window
     *
     * This method retrieves a journey by its ID and prepares the view for editing.
     *
     * @param int $id The ID of the journey to be edited.
     * @return array An array containing the success status and the Journey and Competition objects.
     */

    public function edit($id)
    {
        /** @var Journey $journey */
        $journey = Journey::getById($id);

        if (!$journey) return $this->notFoundError;

        /** @var Competition $competition */
        $competition = Competition::getById($journey->getCompetitionId());

        $this->view = "admin/journey/edit";

        return [
            'object' => $journey,
            'competition' => [
                'startDate' => $competition->getStartDate(),
                'endDate' => $competition->getEndDate()
            ]
        ];
    }

    /**
     * Update journey from POST
     *
     * This method updates a journey's information based on the submitted form data.
     * It validates the input and ensures the date is within the competition's range.
     *
     * @param int $id The ID of the journey to be updated.
     * @return Competition An instance of the Competition object after updating the journey.
     */

    public function update($id)
    {
        $this->view = 'admin/competition/details';

        $validation = self::checkRequiredFields(array('competitionId', 'name', 'date', 'inscriptionsLimit'));

        if (!$validation['success']) {
            return $validation;
        }

        $columns = [
            'name' => $_POST['name'],
            'inscriptionsLimit' => $_POST['inscriptionsLimit'],
            'date' => $_POST['date']
        ];

        /** @var Journey $journey */
        $journey = Journey::getById($id);

        if (!$journey) return $this->notFoundError;

        /** @var Competition $competition */
        $competition = Competition::getById($journey->getCompetitionId());

        if ($columns['date'] > $competition->getEndDate() || $columns['date'] < $competition->getStartDate()) {
            return [
                'success' => false,
                'error' => 'La fecha de esta jornada está fuera del rango de fechas de la competición',
                'object' => $journey
            ];
        }

        if (!Journey::updateFromId($columns, $id)) return $this->notFoundError;

        return Competition::fill($journey->getCompetitionId());
    }

    /**
     * Update from post and return partial view on ajax request
     *
     * This method handles AJAX requests for updating a journey. It returns a
     * partial view containing the updated journey information.
     *
     * @param int $id The ID of the journey to be updated.
     * @return array An array containing the success status and the updated Journey object.
     */

    public function ajaxUpdate($id)
    {
        $result = $this->update($id);

        if (!$result['success']) return $result;

        $this->view = 'admin/journey/details';

        return [
            'success' => true,
            'object' => Journey::fill($id)
        ];
    }

    /**
     * Load view Add session to journey
     *
     * This method prepares the view for adding a session to a journey.
     *
     * @param int $id The ID of the journey to which a session will be added.
     * @return array An array containing the success status and the Journey object.
     */

    public function addSession($id)
    {
        $journey = Journey::getById($id);

        $this->view = 'admin/session/add';

        if (!$journey) return $this->notFoundError;

        return [
            'success' => true,
            'object' => $journey
        ];
    }
}

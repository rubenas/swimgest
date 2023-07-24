<?php

require_once './controller/baseController.php';

class AdminJourneyController extends BaseController
{

    /*Create a Journey Object from Post form*/

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

        /**@var Competition $competition */
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

    /*Add journey to DB*/
    
    public function add()
    {
        $this->view = 'admin/competition/details';

        $journey = self::fromPost();

        Journey::add($journey['object']);

        return Competition::fill($journey['object']->getCompetitionId());
    }

    /* Show remove confirmation window */

    public function removeConfirm($id)
    {
        $this->view = 'admin/journey/remove';

        $journey = Journey::getById($id);

        if(!$journey) return $this->notFoundError;

        return [
            'success' => true,
            'object' => $journey
        ];
    }

    /*Remove journey from DB */

    public function remove($id)
    {
        /**@var Journey $journey */
        $journey = Journey::getById($id);

        if (!$journey) return $this->notFoundError;

        Journey::remove($id);

        $this->view = 'admin/competition/details';

        return Competition::fill($journey->getCompetitionId());
    }

    /**Show edit journey window */

    public function edit($id)
    {

        /**@var Journey $journey */
        $journey = Journey::getById($id);

        if (!$journey) return $this->notFoundError;

        /**@var Competition $competition */
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

    /*Update journey from POST*/
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

        /**@var Journey $journey */
        $journey = Journey::getById($id);

        if (!$journey) return $this->notFoundError;

        /**@var Competition $competition */
        $competition = Competition::getById($journey->getCompetitionId());

        if ($columns['date'] > $competition->getEndDate() || $columns['date'] < $competition->getStartDate()) {

            return [
                'success' => false,
                'error' => 'La fecha de esta jornada está fuera del rango de fechas de la competición',
                'object' => $journey
            ];
        }

        if (!Journey::updateFromId($columns,$id)) return $this->notFoundError;

        return Competition::fill($journey->getCompetitionId());
    }

    /*Update from post and return partial view on ajax request */

    public function ajaxUpdate($id)
    {
        $result = $this->update($id);

        if(!$result['sucess']) return $result;

        $this->view = 'admin/journey/details';

        return [
            'success' => true,
            'object' => Journey::fill($id)
        ];
    }

    /**Load view Add session to journey */

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

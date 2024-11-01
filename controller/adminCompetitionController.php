<?php

require_once './controller/baseController.php';

/**
 * Controller for managing competitions in the admin panel.
 * This controller handles competition-related actions such as 
 * listing, creating, editing, and deleting competitions.
 */

class AdminCompetitionController extends BaseController
{

    /**
     * List competitions.
     *
     * @return array Returns an array with success status and a list of competitions sorted by startDate.
     */

    public function list()
    {
        $this->view = 'admin/competition/list';

        return [
            'success' => true,
            'object' => Competition::getAll([], ['startDate'])
        ];
    }

    /**
     * Create a Competition Object from POST form.
     *
     * @return array Returns an array with success status and the competition object created from the form.
     */

    public static function fromPost()
    {
        $validation = self::checkRequiredFields(array('name', 'place', 'inscriptionsLimit', 'startDate', 'endDate', 'inscriptionsDeadLine'));

        if (!$validation['success']) return $validation;

        $competition = new Competition();

        $competition->setName($_POST['name']);
        $competition->setPlace($_POST['place']);
        $competition->setInscriptionsLimit($_POST['inscriptionsLimit']);
        $competition->setStartDate($_POST['startDate']);
        $competition->setEndDate($_POST['endDate']);
        $competition->setDeadLine($_POST['inscriptionsDeadLine']);

        if ($_FILES['picture']['size'] != 0) {
            require_once './utils/uploadPicture.php';

            $route = './public/img/competitions/' . uniqid("comp");
            $imageRoute = uploadPicture('picture', $route);

            if (isset($imageRoute['success']) && !$imageRoute['success']) {
                $competition->setPicture(Competition::DEFAULT_PICTURE);
                return $imageRoute;
            }

            $competition->setPicture($imageRoute);
        } else {
            $competition->setPicture(Competition::DEFAULT_PICTURE);
        }

        $competition->setLocation(isset($_POST['location']) ? $_POST['location'] : null);
        $competition->setDescription(isset($_POST['description']) ? $_POST['description'] : null);
        $competition->setState(Competition::DEFAULT_STATE);

        if ($competition->getEndDate() < $competition->getStartDate()) {
            return [
                'success' => false,
                'error' => 'La fecha de inicio debe ser anterior a la de fin'
            ];
        }

        if ($competition->getStartDate() <= $competition->getDeadLine()) {
            return [
                'success' => false,
                'error' => 'La fecha de cierre de inscripciones debe ser anterior a la de inicio'
            ];
        }

        return [
            "success" => true,
            "object" => $competition
        ];
    }

    /**
     * Add competition to the database.
     *
     * @return array Returns an array with success status and the added competition object.
     */

    public function add()
    {
        $competition = self::fromPost();

        if ($competition['success']) {
            $result = Competition::add($competition['object']);
        } else {
            $competition['object'] = Competition::getAll('', 'startDate');
            return $competition;
        }

        $this->view = 'admin/competition/details';
        $competition['object']->setId($result['id']);

        return $competition;
    }

    /**
     * Show remove confirmation window.
     *
     * @param int $id The competition ID.
     * @return array Returns an array with success status and the competition object.
     */

    public function removeConfirm($id)
    {
        $this->view = 'admin/competition/remove';

        $competition = Competition::getById($id);

        if (!$competition) return $this->notFoundError;

        return [
            'success' => true,
            'object' => $competition
        ];
    }

    /**
     * Remove competition from the database.
     *
     * @param int $id The competition ID.
     * @return array Returns the result of the list method.
     */

    public function remove($id)
    {
        /** @var Competition $competition */
        $competition = Competition::getById($id);

        if (!$competition) return $this->notFoundError;

        if ($competition->getPicture() != Competition::DEFAULT_PICTURE) unlink($competition->getPicture());

        Competition::remove($id);

        return $this->list();
    }

    /**
     * Show edit competition window.
     *
     * @param int $id The competition ID.
     * @return array Returns an array with success status and the competition object.
     */

    public function edit($id)
    {
        $competition = Competition::getById($id);

        if (!$competition) return $this->notFoundError;

        $this->view = "admin/competition/edit";

        return [
            'success' => true,
            'object' => $competition
        ];
    }

    /**
     * Update competition from POST.
     *
     * @param int $id The competition ID.
     * @return array Returns an array with success status and the updated competition object.
     */

    public function update($id)
    {
        $this->view = 'admin/competition/details';

        $validation = self::checkRequiredFields(array('name', 'place', 'inscriptionsLimit', 'startDate', 'endDate', 'deadLine'));

        if (!$validation['success']) {
            return $validation;
        }

        /** @var Competition $competition */
        $competition = Competition::getById($id);

        if (!$competition) return $this->notFoundError;

        $columns = [
            'name' => $_POST['name'],
            'place' => $_POST['place'],
            'inscriptionsLimit' => $_POST['inscriptionsLimit'],
            'startDate' => $_POST['startDate'],
            'endDate' => $_POST['endDate'],
            'deadLine' => $_POST['deadLine'],
            'location' => isset($_POST['location']) ? $_POST['location'] : null,
            'description' => isset($_POST['description']) ? $_POST['description'] : null
        ];

        if ($columns['endDate'] < $columns['startDate']) {
            return [
                'success' => false,
                'error' => 'La fecha de inicio debe ser anterior a la de fin',
                'object' => Competition::fill($id)['object']
            ];
        }

        if ($columns['startDate'] <= $columns['deadLine']) {
            return [
                'success' => false,
                'error' => 'La fecha de cierre de inscripciones debe ser anterior a la de inicio',
                'object' => Competition::fill($id)['object']
            ];
        }

        if (!Competition::updateFromId($columns, $id)) return $this->notFoundError;

        return Competition::fill($id);
    }


    /**
     * Load view to add a journey to a competition.
     *
     * @param int $id The competition ID.
     * @return array Returns an array with success status and the competition object.
     */

    public function addJourney($id)
    {
        $competition = Competition::getById($id);
        $this->view = 'admin/journey/add';

        if (!$competition) {
            return $this->notFoundError;
        }

        return [
            'success' => true,
            'object' => $competition
        ];
    }

    /**
     * Load view for competition details.
     *
     * @param int $id The competition ID.
     * @return array Returns the competition details.
     */

    public function details($id)
    {
        $this->view = 'admin/competition/details';
        return Competition::fill($id);
    }

    /**
     * Show the window to add a picture to a competition.
     *
     * @param int $id The competition ID.
     * @return array Returns an array with success status and the competition object.
     */

    public function showAddPicture($id)
    {
        $competition = Competition::getById($id);

        if (!$competition) return $this->notFoundError;

        $this->view = "admin/competition/addPicture";

        return [
            'success' => true,
            'object' => $competition
        ];
    }

    /**
     * Update the competition picture.
     *
     * @param int $id The competition ID.
     * @return array Returns the result of the update operation, including success status and the competition object.
     */

    public function updatePicture($id)
    {
        require_once './utils/uploadPicture.php';

        $this->view = 'admin/competition/details';

        /** @var Competition $competition */
        $competition = Competition::getById($id);

        if ($competition->getPicture() != Competition::DEFAULT_PICTURE) {
            unlink($competition->getPicture());
        }

        $route = './public/img/competitions/' . uniqid("comp");

        $imageRoute = uploadPicture('competition-picture', $route);

        if (isset($imageRoute['success']) && !$imageRoute['success']) {
            $imageRoute['object'] = Competition::fill($id)['object'];
            return $imageRoute;
        }

        if (Competition::updateFromId(['picture' => $imageRoute], $id)) {
            return [
                'success' => true,
                'object' => Competition::fill($id)['object']
            ];
        }

        return [
            'success' => false,
            'error' => 'No se ha podido añadir la ruta de la imagen a la base de datos',
            'object' => Competition::fill($id)['object']
        ];
    }

    /**
     * Update the competition picture via AJAX request.
     *
     * @param int $id The competition ID.
     * @return array Returns the result of the update operation.
     */

    public function ajaxUpdatePicture($id)
    {
        $data = $this->updatePicture($id);
        $this->view = 'admin/competition/picture';
        return $data;
    }

    /**
     * Show the window to remove a picture from a competition.
     *
     * @param int $id The competition ID.
     * @return array Returns an array with success status and the competition object.
     */

    public function showRemovePicture($id)
    {
        $competition = Competition::getById($id);

        if (!$competition) return $this->notFoundError;

        $this->view = "admin/competition/removePicture";

        return [
            'success' => true,
            'object' => $competition
        ];
    }

    /**
     * Remove a picture from the folder and database.
     *
     * @param int $id The competition ID.
     * @return array Returns the result of the removal operation.
     */

    public function removePicture($id)
    {
        $this->view = 'admin/competition/details';

        /** @var Competition $competition */
        $competition = Competition::getById($id);

        if (!$competition) {
            return $this->notFoundError;
        }

        if ($competition->getPicture() == Competition::DEFAULT_PICTURE) {
            return [
                'success' => false,
                'error' => 'No puedes borrar la imagen por defecto',
                'object' => Competition::fill($id)['object']
            ];
        }

        if (!unlink($competition->getPicture())) {
            return [
                'success' => false,
                'error' => 'No se ha podido borrar la imagen de perfil',
                'object' => Competition::fill($id)['object']
            ];
        }

        if (!Competition::updateFromId(['picture' => Competition::DEFAULT_PICTURE], $id)) {
            return [
                'success' => false,
                'error' => 'No se ha podido borrar la ruta de la imagen de la base de datos',
                'object' => Competition::fill($id)['object']
            ];
        }

        $competition->setPicture(Competition::DEFAULT_PICTURE);

        return [
            'success' => true,
            'object' => Competition::fill($id)['object']
        ];
    }

    /**
     * Remove a picture via AJAX request.
     *
     * @param int $id The competition ID.
     * @return array Returns the result of the removal operation.
     */

    public function ajaxRemovePicture($id)
    {
        $data = $this->removePicture($id);
        $this->view = 'admin/competition/picture';
        return $data;
    }

    /**
     * Update the state of a competition.
     *
     * @param int $id The competition ID.
     * @return array Returns the result of the update operation.
     */

    public function updateState($id)
    {
        $validation = self::checkRequiredFields(['state']);

        if (!$validation) return $validation;

        Competition::updateFromId(['state' => $_POST['state']], $id);

        return $this->list();
    }

    /**
     * Update the state of a competition via AJAX request.
     *
     * @param int $id The competition ID.
     * @return array Returns the result of the update operation.
     */

    public function ajaxUpdateState($id)
    {
        $validation = self::checkRequiredFields(['state']);

        if (!$validation) return $validation;

        Competition::updateFromId(['state' => $_POST['state']], $id);

        $this->view = 'admin/competition/stateForm';

        return [
            'success' => true,
            'object' => Competition::getById($id)
        ];
    }

    /**
     * Displays the confirmation page for sending an email for a specific competition.
     *
     * This function sets the view for the confirmation page and retrieves the
     * details of the competition and all available email templates.
     *
     * @param int $id The ID of the competition for which the confirmation is being shown.
     * @return array An associative array containing the competition and email template data along with a success status.
     */
    
    public function showSendEmailConfirm($id)
    {
        $this->view = 'admin/competition/sendEmailConfirm';

        return [
            'success' => true,
            'competition' => Competition::getById($id),
            'emails' => Email::getAll()
        ];
    }

    /**
     * Displays the preview of a selected email template with data for a specific competition.
     *
     * This function sets the view for email checking, retrieves the selected email template,
     * and if the competition exists, customizes the email content using competition-specific information.
     * If the competition is not found, a not found error is returned.
     *
     * @param int $competitionId The ID of the competition for which the email is being previewed.
     * @return array Returns an associative array with success status, the competition, and the customized email,
     *               or a not found error if the competition does not exist.
     */

    public function showCheckEmail($competitionId)
    {
        $this->view = 'admin/competition/checkEmail';

        $email = Email::getById($_POST['template-id']);

        if (!$email) {
            $email = new Email();
        }

        $competition = Competition::getById($competitionId);

        if (!$competition) {
            return $this->notFoundError;
        } else {
            require_once './utils/makeEmailBody.php';

            $result = makeEmail($email->getSubject(), $email->getBody(), $competition);

            $email->setSubject($result['subject']);
            $email->setBody($result['body']);

            return [
                'success' => true,
                'competition' => $competition,
                'email' => $email
            ];
        }
    }


    /**
     * Sends an email to all swimmers
     *
     * @return array with success or error mesage
     */

    public function sendToAll()
    {

        require_once './utils/sendEmail.php';
        require './utils/config.php';

        $swimmers = Swimmer::getAll();

        $recipients = [];

        foreach ($swimmers as $swimmer) {

            $recipients[] = $swimmer->getEmail();
        }

        $this->view = 'admin/competition/list';

        $result = sendEmail($recipients, $_POST['subject'], $_POST['body'], $smtpConfig);

        if (!$result['success']) {

            $result['object'] = Competition::getAll();
            return $result;
        }

        return [
            'success' => true,
            'msg' => 'Mensaje enviado correctamente',
            'object' => Competition::getAll()
        ];
    }
}

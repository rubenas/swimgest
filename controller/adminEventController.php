<?php

require_once './controller/baseController.php';

/**
 * Controller for managing events in the admin panel.
 * This controller handles event-related actions such as listing,
 * creating, editing, and deleting events.
 */

class AdminEventController extends BaseController
{
    /**
     * List events.
     *
     * @return array Returns an array with success status and the list of events.
     */

    public function list()
    {
        $this->view = 'admin/event/list';
        $list = Event::getAll(['parentId IS NULL'], ['startDate']);

        return [
            'success' => true,
            'object' => $list
        ];
    }

    /**
     * Create an Event object from POST form data.
     *
     * @return array Returns an array with success status and the event object or error messages.
     */

    public static function fromPost()
    {
        $validation = self::checkRequiredFields(array('name'));

        if (!$validation['success']) return $validation;

        $event = new Event();
        $event->setName($_POST['name']);
        $event->setPlace(isset($_POST['place']) ? $_POST['place'] : NULL);
        $event->setStartDate((isset($_POST['startDate']) && $_POST['startDate'] != '') ? $_POST['startDate'] : NULL);
        $event->setEndDate((isset($_POST['endDate']) && $_POST['endDate'] != '') ? $_POST['endDate'] : NULL);
        $event->setDeadLine((isset($_POST['eventDeadLine']) && $_POST['eventDeadLine'] != '') ? $_POST['eventDeadLine'] : NULL);

        if (isset($_FILES['picture']) && $_FILES['picture']['size'] != 0) {
            require_once './utils/uploadPicture.php';
            $route = './public/img/events/' . uniqid("event");
            $imageRoute = uploadPicture('picture', $route);

            if (isset($imageRoute['success']) && !$imageRoute['success']) {
                $event->setPicture(Event::DEFAULT_PICTURE);
                return $imageRoute;
            }

            $event->setPicture($imageRoute);
        } else {
            $event->setPicture(Event::DEFAULT_PICTURE);
        }

        $event->setLocation(isset($_POST['location']) ? $_POST['location'] : NULL);
        $event->setDescription(isset($_POST['description']) ? $_POST['description'] : NULL);
        $event->setparentId(isset($_POST['parentId']) ? $_POST['parentId'] : NULL);
        $event->setState(isset($_POST['parentId']) ? NULL : Event::DEFAULT_STATE);

        if ($event->getStartDate() != NULL && $event->getEndDate() != NULL) {
            if ($event->getEndDate() < $event->getStartDate()) {
                return [
                    'success' => false,
                    'error' => 'La fecha de inicio debe ser anterior a la de fin'
                ];
            }
        }

        if ($event->getStartDate() != NULL && $event->getDeadLine() != NULL) {
            if ($event->getStartDate() <= $event->getDeadLine()) {
                return [
                    'success' => false,
                    'error' => 'La fecha de cierre de inscripciones debe ser anterior a la de inicio'
                ];
            }
        }

        return [
            "success" => true,
            "object" => $event
        ];
    }

    /**
     * Add an event to the database.
     *
     * @return array Returns the result of the add operation, including the event details.
     */

    public function add()
    {
        $event = self::fromPost();

        if ($event['success']) {
            $result = Event::add($event['object']);
        } else {
            $event['object'] = Event::getAll(['parentId IS NULL'], ['startDate']);
            $this->view = 'admin/event/list';
            return $event;
        }

        $this->view = 'admin/event/details';
        $event['object']->setId($result['id']);

        return Event::fill(Event::getTopParent($event['object'])->getId());
    }

    /**
     * Add an event to the database on AJAX request.
     *
     * @return array Returns the result of the add operation, including the event details.
     */

    public function ajaxAdd()
    {
        $event = self::fromPost();

        if ($event['success']) {
            $result = Event::add($event['object']);
        } else {
            $event['object'] = Event::getAll(['parentId IS NULL'], ['startDate']);
            $this->view = 'admin/event/list';
            return $event;
        }

        $this->view = 'admin/event/subEventDetails';
        $event['object']->setId($result['id']);

        return Event::fill($event['object']->getParentId());
    }

    /**
     * Show the remove confirmation window for an event.
     *
     * @param int $id The event ID.
     * @return array Returns an array with success status and the event object.
     */

    public function removeConfirm($id)
    {
        $this->view = 'admin/event/remove';
        $event = Event::getById($id);

        if (!$event) return $this->notFoundError;

        return [
            'success' => true,
            'object' => $event
        ];
    }

    /**
     * Remove an event from the database.
     *
     * @param int $id The event ID.
     * @return array Returns the result of the removal operation.
     */

    public function remove($id)
    {
        /** @var Event $event */
        $event = Event::getById($id);

        if (!$event) return $this->notFoundError;

        if ($event->getPicture() != Event::DEFAULT_PICTURE) unlink($event->getPicture());

        $topParent = Event::getTopParent($event);
        Event::remove($id);

        if ($topParent->getId() == $event->getId()) return $this->list();

        $this->view = 'admin/event/details';

        return Event::fill($topParent->getId());
    }

    /**
     * Load view event details
     *
     * @param int $id The ID of the event to be displayed.
     * @return array The details of the event.
     */

    public function details($id)
    {
        $this->view = 'admin/event/details';
        return Event::fill($id);
    }

    /**
     * Show edit event window
     *
     * @param int $id The ID of the event to be edited.
     * @return array|mixed An array containing the success status and the event object or a not found error.
     */

    public function edit($id)
    {
        $event = Event::getById($id);

        if (!$event) return $this->notFoundError;

        $this->view = "admin/event/edit";

        return [
            'success' => true,
            'object' => $event
        ];
    }

    /**
     * Update event from POST
     *
     * @param int $id The ID of the event to be updated.
     * @return array An array containing the success status, error messages if any, and the updated event object.
     */

    public function update($id)
    {
        $this->view = 'admin/event/details';

        $validation = self::checkRequiredFields(array('name'));

        if (!$validation['success']) return $validation;

        $columns = [
            'name' => $_POST['name'],
            'place' => isset($_POST['place']) ? $_POST['place'] : NULL,
            'startDate' => (isset($_POST['startDate']) && $_POST['startDate'] != '') ? $_POST['startDate'] : NULL,
            'endDate' => (isset($_POST['endDate']) && $_POST['endDate'] != '') ? $_POST['endDate'] : NULL,
            'deadLine' => isset($_POST['deadLine']) ? $_POST['deadLine'] : NULL,
            'location' => isset($_POST['location']) ? $_POST['location'] : NULL,
            'description' => isset($_POST['description']) ? $_POST['description'] : NULL
        ];

        if ($columns['endDate'] != NULL && $columns['startDate'] != NULL) {
            if ($columns['endDate'] < $columns['startDate']) {
                return [
                    'success' => false,
                    'error' => 'The start date must be earlier than the end date',
                    'object' => Event::fill($id)['object']
                ];
            }
        }

        if ($columns['startDate'] != NULL && $columns['deadLine'] != NULL) {
            if ($columns['startDate'] <= $columns['deadLine']) {
                return [
                    'success' => false,
                    'error' => 'The registration deadline must be earlier than the start date',
                    'object' => Event::fill($id)['object']
                ];
            }
        }

        if (!Event::updateFromId($columns, $id)) return $this->notFoundError;

        $event = Event::getById($id);

        return Event::fill(Event::getTopParent($event)->getId());
    }

    /**
     * Show add picture window
     *
     * @param int $id The ID of the event for which the picture is to be added.
     * @return array|mixed An array containing the success status and the event object or a not found error.
     */

    public function showAddPicture($id)
    {
        $event = Event::getById($id);

        if (!$event) return $this->notFoundError;

        $this->view = "admin/event/addPicture";

        return [
            'success' => true,
            'object' => $event
        ];
    }

    /**
     * Show remove picture window
     *
     * @param int $id The ID of the event for which the picture is to be removed.
     * @return array|mixed An array containing the success status and the event object or a not found error.
     */

    public function showRemovePicture($id)
    {
        $event = Event::getById($id);

        if (!$event) return $this->notFoundError;

        $this->view = "admin/event/removePicture";

        return [
            'success' => true,
            'object' => $event
        ];
    }

    /**
     * Update event picture
     *
     * @param int $id The ID of the event for which the picture is to be updated.
     * @return array An array containing the success status, error messages if any, and the updated event object.
     */

    public function updatePicture($id)
    {
        require_once './utils/uploadPicture.php';

        $this->view  = 'admin/event/details';

        /** @var Event $event */
        $event = Event::getById($id);

        if ($event->getPicture() != Event::DEFAULT_PICTURE) unlink($event->getPicture());

        $route = './public/img/events/' . uniqid("event");

        $imageRoute = uploadPicture('event-picture', $route);

        if (isset($imageRoute['success']) && !$imageRoute['success']) {
            $imageRoute['object'] = Event::fill($id)['object'];
            return $imageRoute;
        }

        if (Event::updateFromId(['picture' => $imageRoute], $id)) {
            return [
                'success' => true,
                'object' => Event::fill($id)['object']
            ];
        }

        return [
            'success' => false,
            'error' => 'Could not add the image path to the database',
            'object' => Event::fill($id)['object']
        ];
    }

    /**
     * Update event picture via AJAX request
     *
     * @param int $id The ID of the event for which the picture is to be updated.
     * @return array The response data from the updatePicture method.
     */

    public function ajaxUpdatePicture($id)
    {
        $data =  $this->updatePicture($id);
        $this->view  = 'admin/event/picture';
        return $data;
    }

    /**
     * Remove event picture from DB and files
     *
     * @param int $id The ID of the event for which the picture is to be removed.
     * @return array An array containing the success status, error messages if any, and the updated event object.
     */

    public function removePicture($id)
    {
        $this->view = 'admin/event/details';

        /** @var Event $event */
        $event = Event::getById($id);

        if (!$event) return $this->notFoundError;

        if ($event->getPicture() == Event::DEFAULT_PICTURE) {
            return [
                'success' => false,
                'error' => 'You cannot delete the default picture',
                'object' => Event::fill($id)['object']
            ];
        }

        if (!unlink($event->getPicture())) {
            return [
                'success' => false,
                'error' => 'Could not delete the profile picture',
                'object' =>  Event::fill($id)['object']
            ];
        }

        if (!Event::updateFromId(['picture' => Event::DEFAULT_PICTURE], $id)) {
            return [
                'success' => false,
                'error' => 'Could not delete the image path from the database',
                'object' => Event::fill($id)['object']
            ];
        }

        $event->setPicture(Event::DEFAULT_PICTURE);

        return [
            'success' => true,
            'object' => Event::fill($id)['object']
        ];
    }

    /**
     * Remove picture on AJAX request
     *
     * @param int $id The ID of the event for which the picture is to be removed.
     * @return array The response data from the removePicture method.
     */

    public function ajaxRemovePicture($id)
    {
        $data = $this->removePicture($id);
        $this->view  = 'admin/event/picture';
        return $data;
    }

    /**
     * Load view to add a sub-event to an event
     *
     * @param int $id The ID of the event to which the sub-event will be added.
     * @return array An array containing the success status and the event object or a not found error.
     */

    public function addSubEvent($id)
    {
        $event = Event::getById($id);
        $this->view = 'admin/event/addSubEvent';

        if (!$event) return $this->notFoundError;

        return [
            'success' => true,
            'object' => $event
        ];
    }

    /**
     * Show edit sub-event window
     *
     * @param int $id The ID of the sub-event to be edited.
     * @return array|mixed An array containing the event object or a not found error.
     */

    public function editSubEvent($id)
    {
        $event = Event::getById($id);

        if (!$event) return $this->notFoundError;

        $this->view = "adminEvent/editSubEvent";

        return $event;
    }

    /**
     * Show add question window to Event
     *
     * @param int $id The ID of the event to which the question will be added.
     * @return array An array containing the success status and the event object or a not found error.
     */

    public function addQuestion($id)
    {
        $event = Event::getById($id);

        if (!$event) return $this->notFoundError;

        $this->view = 'admin/question/addToEvent';

        return [
            'success' => true,
            'object' => $event
        ];
    }

    /**
     * Update event state
     *
     * @param int $id The ID of the event to be updated.
     * @return array An array containing the result of the update operation.
     */

    public function updateState($id)
    {
        $validation = self::checkRequiredFields(['state']);

        if (!$validation) return $validation;

        Event::updateFromId(['state' => $_POST['state']], $id);

        return $this->list();
    }

    /**
     * Update event state via AJAX request
     *
     * @param int $id The ID of the event to be updated.
     * @return array An array containing the success status and the updated event object.
     */

    public function ajaxUpdateState($id)
    {
        $validation = self::checkRequiredFields(['state']);

        if (!$validation) return $validation;

        Event::updateFromId(['state' => $_POST['state']], $id);

        $this->view = 'admin/event/stateForm';

        return [
            'success' => true,
            'object' => Event::getById($id)
        ];
    }

    /**
     * Displays the confirmation page for sending an email for a specific event.
     *
     * This function sets the view for the confirmation page and retrieves the
     * details of the event and all available email templates.
     *
     * @param int $id The ID of the event for which the confirmation is being shown.
     * @return array An associative array containing the event and email template data along with a success status.
     */

    public function showSendEmailConfirm($id)
    {
        $this->view = 'admin/event/sendEmailConfirm';

        return [
            'success' => true,
            'event' => Event::getById($id),
            'emails' => Email::getAll()
        ];
    }

    /**
     * Displays the preview of a selected email template with data for a specific event.
     *
     * This function sets the view for email checking, retrieves the selected email template,
     * and if the event exists, customizes the email content using event-specific information.
     * If the event is not found, a not found error is returned.
     *
     * @param int $eventId The ID of the event for which the email is being previewed.
     * @return array Returns an associative array with success status, the event, and the customized email,
     *               or a not found error if the event does not exist.
     */

    public function showCheckEmail($eventId)
    {
        $this->view = 'admin/event/checkEmail';

        $email = Email::getById($_POST['template-id']);

        if (!$email) {
            $email = new Email();
        }

        $event = Event::getById($eventId);

        if (!$event) {
            return $this->notFoundError;
        } else {
            require_once './utils/makeEmailBody.php';

            $result = makeEmail($email->getSubject(), $email->getBody(), $event);

            $email->setSubject($result['subject']);
            $email->setBody($result['body']);

            return [
                'success' => true,
                'event' => $event,
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

        $this->view = 'admin/event/list';

        $result = sendEmail($recipients, $_POST['subject'], $_POST['body'], $smtpConfig);

        if (!$result['success']) {

            $result['object'] = Event::getAll(['parentId IS NULL'], ['startDate']);
            return $result;
        }

        return [
            'success' => true,
            'msg' => 'Mensaje enviado correctamente',
            'object' => Event::getAll(['parentId IS NULL'], ['startDate'])
        ];
    }
}

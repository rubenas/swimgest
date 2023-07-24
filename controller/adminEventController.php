<?php

require_once './controller/baseController.php';

class AdminEventController extends BaseController
{

    /** List events */
    public function list()
    {

        $this->view = 'admin/event/list';

        $list =  Event::getAll(['parentId IS NULL'], ['startDate']);

        return [
            'success' => true,
            'object' => $list
        ];
    }

    /*Create a Event Object from Post form*/

    public static function fromPost()
    {
        $validation = self::checkRequiredFields(array('name'));

        if (!$validation['success']) return $validation;

        $event = new Event();

        $event->setName($_POST['name']);

        $event->setPlace(isset($_POST['place']) ? $_POST['place'] : NULL);

        $event->setStartDate((isset($_POST['startDate']) && $_POST['startDate'] != '') ? $_POST['startDate'] : NULL);

        $event->setEndDate((isset($_POST['endDate']) && $_POST['endDate'] != '') ? $_POST['endDate'] : NULL);

        $event->setInscriptionsDeadLine((isset($_POST['inscriptionsDeadLine']) && $_POST['inscriptionsDeadLine'] != '') ? $_POST['inscriptionsDeadLine'] : NULL);

        if (isset($_FILES['picture']) && $_FILES['picture']['size'] != 0) {

            require_once './utils/uploadPicture.php';

            $route = './public/img/events/' . uniqid("event");

            $imageRoute = uploadPicture('picture', $route);

            if (isset($imageRoute['sucess']) && !$imageRoute['success']) {

                $event->setPicture(Event::DEFAULT_PICTURE);

                return $imageRoute;
            }

            $event->setPicture($imageRoute);
        } else $event->setPicture(Event::DEFAULT_PICTURE);

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

        if ($event->getStartDate() != NULL && $event->getInscriptionsDeadLine() != NULL) {

            if ($event->getStartDate() <= $event->getInscriptionsDeadLine()) {

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

    /*Add event to DB*/

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

    /*Add event to DB on ajax request*/

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


    /* Show remove confirmation window */

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

    /*Remove event from DB */

    public function remove($id)
    {
        /**@var Event $event */
        $event = Event::getById($id);

        if (!$event) return $this->notFoundError;

        if ($event->getPicture() != Event::DEFAULT_PICTURE) unlink($event->getPicture());

        $topParent = Event::getTopParent($event);

        Event::remove($id);

        if ($topParent->getId() == $event->getId()) return $this->list();

        $this->view = 'admin/event/details';

        return Event::fill($topParent->getId());
    }

    /**Load view event details*/

    public function details($id)
    {

        $this->view = 'admin/event/details';

        return Event::fill($id);
    }

    /**Show edit event window */
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

    /*Update event from POST*/

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
            'inscriptionsDeadLine' => isset($_POST['inscriptionsDeadLine']) ? $_POST['inscriptionsDeadLine'] : NULL,
            'location' => isset($_POST['location']) ? $_POST['location'] : NULL,
            'description' => isset($_POST['description']) ? $_POST['description'] : NULL
        ];

        if ($columns['endDate'] != NULL && $columns['startDate'] != NULL) {

            if ($columns['endDate'] < $columns['startDate']) {

                return [
                    'success' => false,
                    'error' => 'La fecha de inicio debe ser anterior a la de fin',
                    'object' => Event::fill($id)['object']
                ];
            }
        }

        if ($columns['startDate'] != NULL && $columns['inscriptionsDeadLine'] != NULL) {

            if ($columns['startDate'] <= $columns['inscriptionsDeadLine']) {

                return [
                    'success' => false,
                    'error' => 'La fecha de cierre de inscripciones debe ser anterior a la de inicio',
                    'object' => Event::fill($id)['object']
                ];
            }
        }

        if (!Event::updateFromId($columns, $id)) return $this->notFoundError;

        $event = Event::getById($id);

        return Event::fill(Event::getTopParent($event)->getId());
    }

    /**Show add picture window */

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

    /**Show remove picture window */

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

    /**Update event picture */

    public function updatePicture($id)
    {
        require_once './utils/uploadPicture.php';

        $this->view  = 'admin/event/details';

        /**@var Event $event */
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
            'error' => 'No se ha podido añadir la ruta de la imagen a la base de datos',
            'object' => Event::fill($id)['object']
        ];
    }

    public function ajaxUpdatePicture($id)
    {

        $data =  $this->updatePicture($id);

        $this->view  = 'admin/event/picture';

        return $data;
    }

    /**Remove event ficture from DB and files */

    public function removePicture($id)
    {

        $this->view = 'admin/event/details';

        /**@var Event $event */
        $event = Event::getById($id);

        if (!$event) return $this->notFoundError;

        if ($event->getPicture() == Event::DEFAULT_PICTURE) {

            return [
                'success' => false,
                'error' => 'No puedes borrar la imagen por defecto',
                'object' => Event::fill($id)['object']
            ];
        }

        if (!unlink($event->getPicture())) {

            return [
                'success' => false,
                'error' => 'No se ha podido borrar la imagen de perfil',
                'object' =>  Event::fill($id)['object']
            ];
        }

        if (!Event::updateFromId(['picture' => Event::DEFAULT_PICTURE], $id)) {

            return [
                'success' => false,
                'error' => 'No se ha podido borrar la ruta de la imagen de la base de datos',
                'object' => Event::fill($id)['object']
            ];
        }

        $event->setPicture(Event::DEFAULT_PICTURE);

        return [
            'success' => true,
            'object' => Event::fill($id)['object']
        ];
    }

    /**Remove picture on ajax request */

    public function ajaxRemovePicture($id)
    {
        $data = $this->removePicture($id);

        $this->view  = 'admin/event/picture';

        return $data;
    }

    /**Load view Add subEvent to event */

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

    /**Show edit subevent window */

    public function editSubEvent($id)
    {

        $event = Event::getById($id);

        if (!$event) return $this->notFoundError;

        $this->view = "adminEvent/editSubEvent";

        return $event;
    }

    /**Show add question window to Event */

    public function addQuestion($id)
    {
        $event = Event::getById($id);

        if(!$event) return $this->notFoundError;

        $this->view = 'admin/question/addToEvent';

        return [
            'success' => true,
            'object' => $event
        ];
    }
}

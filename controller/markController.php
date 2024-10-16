<?php

include_once './controller/baseController.php';

/**
 * Class MarkController
 * 
 * This class manages the marks related to swimmers, including creating, editing, 
 * displaying, and removing marks, as well as managing FINA points.
 */

class MarkController extends BaseController
{

    /**
     * Create new Mark from post form.
     * 
     * @return array An associative array containing success status and the new Mark object or error message.
     */

    public static function fromPost()
    {
        $validation = self::checkRequiredFields(array('swimmerId', 'style', 'distance', 'pool', 'gender', 'min', 'sec', 'dec', 'category'));

        if (!$validation['success']) {
            return $validation;
        }

        $newMark = new Mark();
        $newMark->setSwimmerId($_POST['swimmerId']);
        $newMark->setStyle($_POST['style']);
        $newMark->setDistance($_POST['distance']);
        $newMark->setPool($_POST['pool']);
        $newMark->setGender($_POST['gender']);
        $newMark->setCategory($_POST['category']);

        $time = '00:';
        $time .=  $_POST['min'] != '' ? $_POST['min'] : '00';
        $time .= ':' . ($_POST['sec'] != '' ? $_POST['sec'] : '00');
        $time .= '.' . ($_POST['dec'] != '' ? $_POST['dec'] : '00');

        $newMark->setTime($time);

        if ($time == '00:00:00.00') {
            return [
                "success" => false,
                "error" => 'La marca no puede ser igual a cero'
            ];
        }

        return [
            "success" => true,
            "object" => $newMark
        ];
    }

    /**
     * Show marks for logged swimmer.
     * 
     * @return array An associative array containing success status and the Swimmer object with marks.
     */

    public function showMarks()
    {
        $id = $this->sessionId();

        $this->view = 'swimmer/marks';

        /** @var Swimmer $swimmer */
        $swimmer = Swimmer::getById($id);

        if (!$swimmer) {
            return $this->notFoundError;
        }

        $swimmer->setMarks(Mark::getFromSwimmerId($id));

        return [
            'success' => true,
            'object' => $swimmer
        ];
    }

    /**
     * Add a new mark.
     * 
     * @return array An associative array containing success status, error message or the Swimmer's marks.
     */

    public function add()
    {
        $mark = self::fromPost();

        $this->view = 'swimmer/marks';

        if ($mark['success']) {

            // Checking if the mark exists
            if (Mark::getFromUqConstraint(
                $mark['object']->getSwimmerId(),
                $mark['object']->getDistance(),
                $mark['object']->getStyle(),
                $mark['object']->getPool()
            )) {

                return [
                    'success' => false,
                    'error' => 'Ya existe una marca para esta prueba, prueba a editarla.',
                    'object' => $this->showMarks($mark['object']->getSwimmerId())['object']
                ];
            }

            if (Mark::addToDB($mark['object'])) {
                return $this->showMarks($mark['object']->getSwimmerId());
            } else {
                return [
                    'success' => false,
                    'error' => 'No se ha podido añadir la marca a la base de datos'
                ];
            }
        } else {
            return $mark;
        }
    }

    /**
     * Remove mark confirmation.
     * 
     * @param int $id The ID of the mark to be removed.
     * @return array An associative array containing success status and the Mark object or error.
     */

    public function removeConfirm($id)
    {
        /** @var Mark $mark */
        $mark = Mark::getById($id);

        if (!$mark) return $this->notFoundError;

        if ($mark->getSwimmerId() != $this->sessionId()) return $this->notPermissionError;

        $this->view = 'swimmer/removeMark';

        return [
            'object' => $mark,
            'success' => true
        ];
    }

    /**
     * Remove a mark from the database.
     * 
     * @param int $id The ID of the mark to be removed.
     * @return array An associative array containing success status, error message or the updated marks.
     */

    public function remove($id)
    {
        $this->view = 'swimmer/marks';

        /** @var Mark $mark */
        $mark = Mark::getById($id);

        if (!$mark) {
            return $this->notFoundError;
        }

        $swimmerId = $mark->getSwimmerId();

        if ($swimmerId != $this->sessionId()) {
            $this->view = 'swimmer/listCompetitions';
            return $this->notPermissionError;
        }

        if (!Mark::remove($id)) {
            return [
                'success' => false,
                'error' => 'No ha sido posible borrar la marca'
            ];
        }

        return [
            'success' => true,
            'object' => $this->showMarks($swimmerId)['object'],
            'msg' => 'Marca eliminada correctamente'
        ];
    }

    /**
     * Edit mark window.
     * 
     * @param int $id The ID of the mark to be edited.
     * @return array An associative array containing success status and the Mark object.
     */

    public function edit($id)
    {
        /** @var Mark $mark */
        $mark = Mark::getById($id);

        if (!$mark) return $this->notFoundError;

        if ($mark->getSwimmerId() != $this->sessionId()) return $this->notPermissionError;

        $this->view = "swimmer/editMark";

        return [
            'object' => $mark,
            'success' => true
        ];
    }

    /**
     * Update mark in the database.
     * 
     * @param int $id The ID of the mark to be updated.
     * @return array An associative array containing success status, error message or the updated marks.
     */

    public function update($id)
    {
        /** @var Mark $mark */
        $mark = Mark::getById($id);

        if (!$mark) {
            return $this->notFoundError;
        }

        if ($mark->getSwimmerId() != $this->sessionId()) {
            $this->view = 'swimmer/listCompetitions';
            return $this->notPermissionError;
        }

        $this->view = 'swimmer/marks';

        $validation = self::checkRequiredFields(array('min', 'sec', 'dec'));

        if (!$validation['success']) {
            return $validation;
        }

        $floatTime = floatval($_POST['min']) * 60 + floatval($_POST['sec']) + floatval($_POST['dec']) / 100;

        /** @var Mark $mark */
        $mark = Mark::update($id, $floatTime);

        if ($mark['success']) {
            return [
                'success' => true,
                'object' => $this->showMarks($mark['object']->getSwimmerId())['object'],
                'msg' => 'Marca actualizada correctamente'
            ];
        } else {
            return [
                'success' => false,
                'error' => 'No ha sido posible editar tu marca'
            ];
        }
    }

    /**
     * Show the FINA calculator.
     */

    public function showFinaCalculator()
    {
        $this->view = "swimmer/finaCalculator";
    }

    /**
     * Calculate FINA points based on the provided criteria.
     * 
     * @return array An associative array containing success status, error message, or calculated marks.
     */

    public function finaPoints()
    {

        $this->view = 'swimmer/finaCalculator';

        $validation = self::checkRequiredFields(['category', 'gender', 'distance', 'style', 'pool']);

        if (!$validation['success']) {

            return $validation;
        }

        $mark = new Mark();
        $mark->setGender($_POST['gender']);
        $mark->setDistance($_POST['distance']);
        $mark->setStyle($_POST['style']);
        $mark->setPool($_POST['pool']);
        $mark->setCategory($_POST['category']);

        if (isset($_POST['finaPoints']) && $_POST['finaPoints'] != '') {

            if (!is_numeric($_POST['finaPoints'])) {

                return [
                    'success' => false,
                    'error' => 'El valor de los puntos FINA debe ser numérico'
                ];
            }

            $wr = WorldRecord::getFromMark($mark);

            if (!$wr) return $this->notFoundError;

            $floatTime = $wr->getFloatTime() / pow($_POST['finaPoints'] / 1000, 1 / 3);

            $mark->setTimeFromFloat($floatTime);

            return [
                'success' => true,
                'mark' => [
                    'min' => $mark->getMinutes(),
                    'sec' => $mark->getSeconds(),
                    'dec' => floor($mark->getMiliseconds() / 10)
                ]
            ];
        }

        $mark = self::fromPost();

        if (!$mark['success']) return $mark;

        return [
            'success' => true,
            'points' => $mark['object']->getFinaPoints()
        ];
    }
}

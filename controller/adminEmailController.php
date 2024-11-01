<?php

require_once './controller/baseController.php';

/**
 * Controller for managing emails in the admin panel.
 * This controller handles email-related actions such as 
 * listing, creating, editing, and deleting emails.
 */

class AdminEmailController extends BaseController
{

    /**
     * List emails.
     *
     * @return array Returns an array with success status and a list of emails sorted by title.
     */

    public function list()
    {
        $this->view = 'admin/email/list';

        return [
            'success' => true,
            'object' => Email::getAll([], ['title'])
        ];
    }

    /**
     * Create an Email Object from POST form.
     *
     * @return array Returns an array with success status and the email object created from the form.
     */

    public static function fromPost()
    {
        $validation = self::checkRequiredFields(array('title', 'subject'));

        if (!$validation['success']) return $validation;

        $email = new Email();

        $email->setTitle($_POST['title']);
        $email->setSubject($_POST['subject']);
        isset($_POST['body']) ? $email->setBody($_POST['body']) : $email->setBody('');
        
        return [
            "success" => true,
            "object" => $email
        ];
    }

    /**
     * Add emails to the database.
     *
     * @return array Returns an array with success status and the added email object.
     */

    public function add()
    {
        $email = self::fromPost();

        if ($email['success']) {

            $result = Email::add($email['object']);
        } else {

            $email['object'] = Email::getAll('', 'title');

            return $email;
        }

        $this->view = 'admin/email/list';
        $email['object']->setId($result['id']);

        return [
            'success' => true,
            'object' => Email::getAll()
        ];
    }

    /**
     * Show remove confirmation window.
     *
     * @param int $id The email ID.
     * @return array Returns an array with success status and the email object.
     */

    public function removeConfirm($id)
    {
        $this->view = 'admin/email/remove';

        $email = Email::getById($id);

        if (!$email) return $this->notFoundError;

        return [
            'success' => true,
            'object' => $email
        ];
    }

    /**
     * Remove email from the database.
     *
     * @param int $id The email ID.
     * @return array Returns the result of the list method.
     */

    public function remove($id)
    {
        /** @var Email $email */
        $email = Email::getById($id);

        if (!$email) return $this->notFoundError;

        Email::remove($id);

        return $this->list();
    }

    /**
     * Show edit email window.
     *
     * @param int $id The email ID.
     * @return array Returns an array with success status and the email object.
     */

    public function edit($id)
    {
        $email = Email::getById($id);

        if (!$email) return $this->notFoundError;

        $this->view = "admin/email/edit";

        return [
            'success' => true,
            'object' => $email
        ];
    }

    /**
     * Update email from POST.
     *
     * @param int $id The competition ID.
     * @return array Returns an array with success status and the updated email object.
     */

    public function update($id)
    {
        $this->view = 'admin/email/list';

        $validation = self::checkRequiredFields(array('title', 'subject'));

        if (!$validation['success']) {
            return $validation;
        }

        /** @var Email $email */
        $email = Email::getById($id);

        if (!$email) return $this->notFoundError;

        $columns = [
            'title' => $_POST['title'],
            'subject' => $_POST['subject'],
            'body' => isset($_POST['body']) ? $_POST['body'] : ''
        ];

        if (!Email::updateFromId($columns, $id)) return $this->notFoundError;

        return $this->list();
    }

}

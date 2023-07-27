<?php

require_once './controller/baseController.php';

class AdminInscriptionController extends BaseController
{
    
    /**Show competition inscriptions */

    public function competition($id)
    {
        $competition = Competition::fill($id);

        if (!$competition['success']) return $this->notFoundError;

        $arrayInscriptions = array();
        $inscribedSwimmers = array();

        foreach ($competition['object']->getJourneys() as $journey){

            foreach ($journey->getSessions() as $session){

                foreach ($session->getRaces() as $race){

                    $inscriptions = Inscription::getAll(['raceId = '.$race->getId()],[]);

                    foreach ($inscriptions as $inscription) {

                        /**@var Swimmer $swimmer */
                        $swimmer = Swimmer::getById($inscription->getSwimmerId());
                        
                        $arrayInscriptions[$race->getId()][] = [
                            'swimmer' => $swimmer->getSurname().', '.$swimmer->getName(),
                            'mark' => $inscription->getMark()
                        ];

                        if(!in_array($swimmer->getSurname().', '.$swimmer->getName(),$inscribedSwimmers)) $inscribedSwimmers[] = $swimmer->getSurname().', '.$swimmer->getName();  
                    }

                    if (isset($arrayInscriptions[$race->getId()])) usort($arrayInscriptions[$race->getId()], fn($a, $b) => $a['swimmer'] <=> $b['swimmer']);

                }
            }
        }

        sort($inscribedSwimmers);

        $this->view = 'admin/inscription/competition';

        return [
            'competition' => $competition['object'],
            'inscriptions' => $arrayInscriptions,
            'inscribedSwimmers' => $inscribedSwimmers
        ];
    }
}
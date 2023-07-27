import { modalMsg } from "./modules/modalMsg.js";

window.addEventListener("load", loadInscriptionCompetitionCheckerSettings);

function loadInscriptionCompetitionCheckerSettings() {

    const form = document.querySelector("[id^='competition-']");

    const button = form.querySelector("[type='submit']");

    if (button != null) {

        const competition = fillCompetition(form);

        button.addEventListener("click",function(event){

            event.preventDefault();

            let result = checkInscription(competition);

            if(!result.success) modalMsg(result.error);
            
            else form.submit();
        });
    }
}

/**Checks inscription from competition object*/

function checkInscription(competition) {

    let error = '';

    let nInscriptionsCompetition = 0;
    
    for (let journey of competition.journeys) {

        let nInscriptionsJourney = 0;

        for (let session of journey.sessions) {

            let nInscriptionsSession = 0;

            for (let race of session.races) {

                if (race.checked && race.getAttribute("isRelay") == "0") {

                    nInscriptionsCompetition ++;
                    nInscriptionsJourney ++;
                    nInscriptionsSession ++;

                    if(!hasMark(race)) error = '<p>Debes proporcionar una marca válida para todas las pruebas en las que te inscribas</p>';
                }
            }

            if (nInscriptionsSession > session.inscriptionsLimit) error = "<p>Has excedido el número máximo de pruebas en la session " + session.name + " de la jornada " + journey.name + "</p>";
        }

        if (nInscriptionsJourney > journey.inscriptionsLimit) error = "<p>Has excedido el número máximo de pruebas en la jornada " + journey.name + "</p>";
    }

    if (nInscriptionsCompetition > competition.inscriptionsLimit) error = "<p>Has excedido el número máximo de pruebas en la competición</p>";

    if (error != '') {

        return {
            'success': false,
            'error': error
        };
    } 

    return {'success': true};
}

/**Check if a race checked has a mark */

function hasMark(race) {

    const raceId = race.getAttribute("raceId");
    
    const divMark = document.getElementById("mark["+ raceId + "]");
    
    const min = parseInt(divMark.querySelector("[name^='min']").value) || 0;
    const sec = parseInt(divMark.querySelector("[name^='sec']").value) || 0;
    const dec = parseInt(divMark.querySelector("[name^='dec']").value) || 0;

    if (min + sec + dec <= 0) {

        race.focus();

        return false;
    }

    return true;
}

/**Fills a competition object with all its attributes */

function fillCompetition(form) {

    const competition = new Competition(form.getAttribute("competitionId"),form.getAttribute("inscriptionsLimit"));

    const sectionsJourney = form.querySelectorAll("[id^='journey-']");

    let journeys = new Array();

    for (let section of sectionsJourney){

        let journey = new Journey(section.getAttribute("journeyId"),section.getAttribute("journeyName"),form.getAttribute("competitionId"),section.getAttribute("inscriptionsLimit"));

        let sectionsSession = section.querySelectorAll("[id^='session-']");

        let sessions = new Array();

        for (let sectionSession of sectionsSession) {

            let session = new Session(sectionSession.getAttribute("sessionId"),sectionSession.getAttribute("sessionName"),journey.id,sectionSession.getAttribute("inscriptionsLimit"));

            let races = sectionSession.querySelectorAll("[name^='race[']");

            session.setRaces(races);

            sessions.push(session);
        }

        journey.setSessions(sessions);

        journeys.push(journey);

    }

    competition.setJourneys(journeys);

    return competition;
}

/**Classes */

class Competition {
    
    constructor(id,inscriptionsLimit) {

        this.id = parseInt(id);
        this.inscriptionsLimit = parseInt(inscriptionsLimit);
    }

    setJourneys(journeys) {
        this.journeys = journeys;
    }
}

class Journey {
    
    constructor(id,name,competitionId,inscriptionsLimit) {

        this.id = parseInt(id);
        this.name = name;
        this.competitionId = parseInt(competitionId);
        this.inscriptionsLimit = parseInt(inscriptionsLimit);
        
    }

    setSessions(sessions) {
        this.sessions = sessions;
    }
}

class Session {
    
    constructor(id,name,journeyId,inscriptionsLimit) {

        this.id = parseInt(id);
        this.name = name;
        this.journeyId = parseInt(journeyId);
        this.inscriptionsLimit = parseInt(inscriptionsLimit);
    }

    setRaces(races) {

        this.races = races;
    }
}

<?php

/**Returns a mmar properly formatted */

function formatMark($time)
{

    $date = new DateTimeImmutable($time);

    $cents = rtrim($date->format('v'),'0');//Remove last 0 character

    if($cents == '') $cents = 0;

    if (intval($date->format('i')) > 0) return intval($date->format('i')).$date->format(':s.').$cents;

    return $date->format('s.').$cents;
}


/*Get formated dates like dd-mm-yyyy*/

function formatDMYDate($date)
{
    if ($date == null) return null;
    
    $date = new DateTimeImmutable($date);

    return $date->format('d-m-Y');
}


/*Get formated dates like dd-mm-yyyy a las hh:mm*/
function formatDMYHMDate($date)
{

    if ($date == null) return null;

    $date = new DateTimeImmutable($date);

    return $date->format('d-m-Y \a \l\a\s H:i');
}

function formatHMDate($time)
{

    $date = new DateTimeImmutable($time);

    return $date->format('H:i');
}
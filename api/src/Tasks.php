<?php

declare (strict_types=1);
require_once __DIR__ . '/activities.php';

/**
 * Hämtar en lista med alla uppgifter och tillhörande aktiviteter 
 * Beroende på indata returneras en sida eller ett datumintervall
 * @param Route $route indata med information om vad som ska hämtas
 * @return Response
 */
function tasklists(Route $route): Response {
    try {
        if (count($route->getParams()) === 1 && $route->getMethod() === RequestMethod::GET) {
            return hamtaSida($route->getParams()[0]);
        }
        if (count($route->getParams()) === 2 && $route->getMethod() === RequestMethod::GET) {
            return hamtaDatum($route->getParams()[0], $route->getParams()[1]);
        }
    } catch (Exception $exc) {
        return new Response($exc->getMessage(), 400);
    }

    return new Response("Okänt anrop", 400);
}

/**
 * Läs av rutt-information och anropa funktion baserat på angiven rutt
 * @param Route $route Rutt-information
 * @param array $postData Indata för behandling i angiven rutt
 * @return Response
 */
function tasks(Route $route, array $postData): Response {
    try {
        if (count($route->getParams()) === 1 && $route->getMethod() === RequestMethod::GET) {
            return hamtaEnskildUppgift($route->getParams()[0]);
        }
        if (count($route->getParams()) === 0 && $route->getMethod() === RequestMethod::POST) {
            return sparaNyUppgift($postData);
        }
        if (count($route->getParams()) === 1 && $route->getMethod() === RequestMethod::PUT) {
            return uppdateraUppgift( $route->getParams()[0], $postData);
        }
        if (count($route->getParams()) === 1 && $route->getMethod() === RequestMethod::DELETE) {
            return raderaUppgift($route->getParams()[0]);
        }
    } catch (Exception $exc) {
        return new Response($exc->getMessage(), 400);
    }
}

/**
 * Hämtar alla uppgifter för en angiven sida
 * @param string $sida
 * @return Response
 */
function hamtaSida(string $sida): Response {
    //kontrollera indata
    $sidnummer=filter_var($sida, FILTER_VALIDATE_INT);

    if($sidnummer===false) {
        $retur=new stdClass();
        $retur->error=['Bad request', 'Ogiltigt sidnummer'];

        return new Response($retur, 400);
    } elseif ($sidnummer<1) {
        $retur=new stdClass();
        $retur->error=['Bad request', 'sidnummmer ska vara större än noll'];

        return new Response($retur, 400);
    }

    //hämta antal poster per sida
    $settings=new Settings();
    $posterPerSida=$settings->recordsPerPage;


    //koppla databas
    $db=connectDb();

    //skicka fråga om antal poster
    $result=$db->query("SELECT COUNT(*) FROM uppgifter");
    $antalRader=$result->fetchColumn();
    $antalSidor= ceil($antalRader/$posterPerSida);

    //kontrollera begärd sida
    if($sidnummer > $antalSidor) {
        $retur=new stdClass();
        $retur->error=['Bad request', "det finns bara $antalSidor"];

        return new Response($retur, 400);

    }

    //skicka fråga om aktuell sida 
    $firstRecord=$sidnummer*$posterPerSida-($posterPerSida-1);
        $result = $db->query("SELECT uppgifter.id, aktivitet_id, date, varaktighet, aktivitet, beskrivning 
FROM uppgifter 
INNER JOIN aktiviteter ON aktiviteter.id=aktivitet_id
ORDER BY date limit $firstRecord, $posterPerSida");

    //returnera svar
    $retur=[];
    foreach($result->fetchALL() as $row) {
        $post=new stdClass();
        $post->id=$row['id'];
        $post->activityId=$row['aktivitet_id'];
        $post->date=$row['date'];
        $post->time=substr( $row['varaktighet'], 0,5);
        $post->activity=$row['aktivitet'];
        $post->description=$row['beskrivning'];
        $retur[]=$post;
    }

    $svar=new stdClass();
    $svar->pages=$antalSidor;
    $svar->tasks=$retur;

    return new Response($svar);
}

/**
 * Hämtar alla poster mellan angivna datum
 * @param string $from
 * @param string $tom
 * @return Response
 */
function hamtaDatum(string $from, string $tom): Response {
    //kontrollerra indata
    $fromDate=DateTimeImmutable::createFromFormat("Y-m-d", $from);
    $tomDate=DateTimeImmutable::createFromFormat("Y-m-d", $tom);

    $err=[];
    if($fromDate===false) {
        $err[]="oglitigt från-datum";
    } elseif($fromDate->format('Y-m-d')!==$from) {
        $err[]="ogiltigt format på från-datum";
    }
    if($tomDate===false) {
        $err[]="oglitigt till-datum";
        } elseif($tomDate->format('Y-m-d')!==$tom) {
        $err[]="ogiltigt format på till-datum";
    } 
    if(count($err)===0 && $fromDate->format('Y-m-d')>$tomDate->format('Y-m-d')) {
        $err[]="från-datum ska vara mindre än till datum";
    }

    if(count($err)>0) {
        array_unshift($err, 'Bad request');
        $retur=new stdClass();
        $retur->error=$err;
        return new Response($retur, 400);
    }


    //koppla databas
    $db=connectDb();

    //skicka fråga 
    $stmt=$db->prepare('SELECT uppgifter.id, aktivitet_id, date, varaktighet, aktivitet, beskrivning 
FROM uppgifter 
INNER JOIN aktiviteter ON aktiviteter.id=aktivitet_id
WHERE date BETWEEN :from AND :to
ORDER BY date');
    $stmt->execute(['from'=>$fromDate->format("Y-m-d"), 'to'=>$tomDate->format("Y-m-d")]);

    //kontrollera svar och returera data
    $retur=[];
    foreach($stmt->fetchALL() as $row) {
        $post=new stdClass();
        $post->id=$row['id'];
        $post->activityId=$row['aktivitet_id'];
        $post->date=$row['date'];
        $post->time=substr( $row['varaktighet'], 0,5);
        $post->activity=$row['aktivitet'];
        $post->description=$row['beskrivning'];
        $retur[]=$post;
    }

    return new Response($retur);
}

/**
 * Hämtar en enskild uppgiftspost
 * @param string $id Id för post som ska hämtas
 * @return Response
 */
function hamtaEnskildUppgift(string $id): Response {
    //kontrollera indata
    $taskId=filter_var($id, FILTER_VALIDATE_INT);

    if($taskId===false) {
        $retur=new stdClass();
        $retur->error=['Bad request', 'Ogiltigt uppgifts-id'];
        return new Response($retur, 400);
    }

    //koppla databas
    $db=connectDb();

    //hämta post
    $stmt=$db->prepare('SELECT uppgifter.id, aktivitet_id, date, varaktighet, aktivitet, beskrivning 
FROM uppgifter 
INNER JOIN aktiviteter ON aktiviteter.id=aktivitet_id
WHERE uppgifter.id=:id');
    $stmt->execute(['id'=>$taskId]);

    //returnera post
    $row=$stmt->fetch();
    if(!$row) {
        $retur=new stdClass();
        $retur->error=['bad request', "anvivet id ($taskId) finns inte i databasen"];
        return new Response($retur, 400);
    }
    $retur=new stdClass();
    $retur->id=$row['id'];
    $retur->date=$row['date'];
    $retur->time=substr( $row['varaktighet'], 0,5);
    $retur->activityId=$row['aktivitet_id'];
    $retur->activity=$row['aktivitet'];
    $retur->description=$row['beskrivning'];

    return new Response($retur);
}

/**
 * Sparar en ny uppgiftspost
 * @param array $postData indata för uppgiften
 * @return Response
 */
function sparaNyUppgift(array $postData): Response {
    
}

/**
 * Uppdaterar en angiven uppgiftspost med ny information 
 * @param string $id id för posten som ska uppdateras
 * @param array $postData ny data att sparas
 * @return Response
 */
function uppdateraUppgift(string $id, array $postData): Response {
    
}

/**
 * Raderar en uppgiftspost
 * @param string $id Id för posten som ska raderas
 * @return Response
 */
function raderaUppgift(string $id): Response {
    //kontrollera indata
    $taskId=filter_var($id, FILTER_VALIDATE_INT);

    if($taskId===false) {
        $retur=new stdClass();
        $retur->error=['Bad request', 'ogiltigt id'];
        return new Response($retur, 400);
    }

    //koppla databas
    $db=connectDb();

    //skicka fråga
    $stmt=$db->prepare('DELETE FROM uppgifter WHERE id=:id');
    $stmt->execute(['id'=>$taskId]);

    //kontrollera svar och returnera svar
    if($stmt->rowCount()===0) {
        $retur=new stdClass();
        $retur->result=false;
        $retur->massage=['radera misslyckades', 'inga poster raderades'];
        return new Response($retur);
    }
    $retur=new stdClass();
        $retur->result=false;
        $retur->massage=['radera lyckades', "{$stmt->rowCount()} poster raderades"];
        return new Response($retur);
}
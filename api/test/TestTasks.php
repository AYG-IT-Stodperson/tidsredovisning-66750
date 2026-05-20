<?php

declare (strict_types=1);
require_once __DIR__ . '/../src/tasks.php';

/**
 * Funktion för att testa alla aktiviteter
 * @return string html-sträng med resultatet av alla tester
 */
function allaTaskTester(): string {
// Kom ihåg att lägga till alla testfunktioner
    $retur = "<h1>Testar alla uppgiftsfunktioner</h1>";
    $retur .= test_HamtaEnUppgift();
    $retur .= test_HamtaUppgifterSida();
    $retur .= test_RaderaUppgift();
    $retur .= test_SparaUppgift();
    $retur .= test_UppdateraUppgifter();
    return $retur;
}

/**
 * Tester för funktionen hämta uppgifter för ett angivet sidnummer
 * @return string html-sträng med alla resultat för testerna 
 */
function test_HamtaUppgifterSida(): string {
    $retur = "<h2>test_HamtaUppgifterSida</h2>";
    try {
        $retur .= "<p class='error'>Inga tester implementerade</p>";
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    }

    return $retur;
}

/**
 * Test för funktionen hämta uppgifter mellan angivna datum
 * @return string html-sträng med alla resultat för testerna
 */
function test_HamtaAllaUppgifterDatum(): string {
    $retur = "<h2>test_HamtaAllaUppgifterDatum</h2>";
    try {
        $retur .= "<p class='error'>Inga tester implementerade</p>";
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    }

    return $retur;
}

/**
 * Test av funktionen hämta enskild uppgift
 * @return string html-sträng med alla resultat för testerna
 */
function test_HamtaEnUppgift(): string {
    $retur = "<h2>test_HamtaEnUppgift</h2>";

    try {
        // testa -1
        $svar=hamtaEnskildUppgift("-1");
        if($svar->getStatus()===400) {
            $retur .="<p class='ok'>hämta uppgift med id=1 returnerade 400, som förväntat</p>";
        } else {
            $retur .="<p class='error'>hämta uppgift med id=1 returnerade {$svar->getStatus()}, 400 förväntades</p>";
        }
        // testa 'sju'
        $svar=hamtaEnskildUppgift("sju");
        if($svar->getStatus()===400) {
            $retur .="<p class='ok'>hämta uppgift med id='sju' returnerade 400, som förväntat</p>";
        } else {
            $retur .="<p class='error'>hämta uppgift med id='sju' returnerade {$svar->getStatus()}, 400 förväntades</p>";
        }
        //testa uppgift som inte finns 
        $db=connectDb();
        $sistaPost=$db->query( 'SELECT MAX(id) FROM uppgifter')->fetchColumn();

        $svar=hamtaEnskildUppgift((string) ($sistaPost + 1));
        if($svar->getStatus()===400) {
            $retur .="<p class='ok'>hämta uppgift med id=" . $sistaPost + 1 . " returnerade 400, som förväntat</p>";
        } else {
            $retur .="<p class='error'>hämta uppgift med id=" . $sistaPost + 1 . " returnerade {$svar->getStatus()}, 400 förväntades</p>";
        }

        //testa uppgift som finns
        $svar=hamtaEnskildUppgift((string) ($sistaPost));
        if($svar->getStatus()===200) {
            $retur .="<p class='ok'>hämta uppgift med id som finns=" . $sistaPost . " returnerade 200, som förväntat</p>";
        } else {
            $retur .="<p class='error'>hämta uppgift med id som finns=" . $sistaPost . " returnerade {$svar->getStatus()}, 200 förväntades</p>";
        }
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    }

    return $retur;
}

/**
 * Test för funktionen spara uppgift
 * @return string html-sträng med alla resultat för testerna
 */
function test_SparaUppgift(): string {
    $retur = "<h2>test_SparaUppgift</h2>";

        $db=connectDb();
    try {
        //skapa transaktion för att inte fylla databasen med skräp
        $db->beginTransaction();

        //sätt upp testdata
        $postData=['date'=>'2020-13-35',
            'time'=>'01:00',
            'activityId'=>-1,
            'description'=>"Test"
            ];

        //test där varifiering misslyckas. -> 400
        $svar=SparaNyUppgift($postData);
        if($svar->getStatus()===400) {
            $retur .="<p class='ok'>spara post med felaktig indata returnerade 400, som förväntat</p>";
        } else {
            $retur .="<p class='error'>spara post med felaktig indata returnerade {$svar->getStatus()}, 400 förväntades</p>";
        }

        //test med felaktig aktivitetsId -> 400
        $aktivitetsId= $db->query ( 'SELECT MAX(id) FROM aktiviteter')->fetchColumn();
        $postData['data']=date('Y-m-d', strtotime('yesterday'));
        $postData['activityId']=$aktivitetsId +1;
        $svar=sparaNyUppgift($postData);
        if($svar->getStatus()===400) {
            $retur .="<p class='ok'>spara post med felaktigt aktivitetsId returnerade 400, som förväntat</p>";
        } else {
            $retur .="<p class='error'>spara post med felaktigt aktivitetsId returnerade {$svar->getStatus()}, 400 förväntades</p>";
        }

        //test utan description ->200
        $postData['activityId']=$aktivitetsId;
        unset($postDate['description']);
        $svar=SparaNyUppgift($postData);
        if($svar->getStatus()===200) {
            $retur .="<p class='ok'>spara post utan description returnerade 200, som förväntat</p>";
        } else {
            $retur .="<p class='error'>sspara post utan description returnerade {$svar->getStatus()}, 200 förväntades</p>";
        }

        //test med description 
        $postData['descriptiom']="Test";
        $svar=SparaNyUppgift($postData);
        if($svar->getStatus()===200) {
            $retur .="<p class='ok'>spara post med description returnerade 200, som förväntat</p>";
        } else {
            $retur .="<p class='error'>sspara post med description returnerade {$svar->getStatus()}, 200 förväntades</p>";
        }
        $retur .= "<p class='error'>Inga tester implementerade</p>";
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    } finally {
        $db->rollBack();
    }

    return $retur;
}

/**
 * Test för funktionen uppdatera befintlig uppgift
 * @return string html-sträng med alla resultat för testerna
 */
function test_UppdateraUppgifter(): string {
    $retur = "<h2>test_UppdateraUppgifter</h2>";

    try {
        $retur .= "<p class='error'>Inga tester implementerade</p>";
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    }

    return $retur;
}

function test_KontrolleraIndata(): string {
    $retur = "<h2>test_KontrolleraIndata</h2>";

    try {
        $retur .= "<p class='error'>Inga tester implementerade</p>";
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    }

    return $retur;
}

/**
 * Test för funktionen radera uppgift
 * @return string html-sträng med alla resultat för testerna
 */
function test_RaderaUppgift(): string {
    $retur = "<h2>test_RaderaUppgift</h2>";

    try {
        $retur .= "<p class='error'>Inga tester implementerade</p>";
    } catch (Exception $ex) {
        $retur .= "<p class='error'>Något gick fel, meddelandet säger:<br> {$ex->getMessage()}</p>";
    }

    return $retur;
}

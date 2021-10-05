<html lang="de-DE">
<body style="font-family: 'Droid Sans', arial, sans-serif;font-size:15px;background-color:#fff5e0; padding:20px;">
<?php
$receiver = "bibliophilus.magicus@gmail.com";
$subject_fail = "Bibspiel-Anfrage: schon vergeben";
$text_fail = "Meldung vom Bibliotheks-Spiel: Nutzer konnte den Code nicht erhalten. \n";
$subject_success = "Bibspiel-Anfrage: Spieler*in startet!";
$text_success = "Meldung vom Bibliotheks-Spiel: Nutzer hat sich einen Code abgeholt und startet das Spiel. \n";
$from = 'webtool@what-the-fact.de';


class BibDB extends SQLite3 {
	function __construct() {
		$this->open('bib1');
	}
}
$db = new BibdB();
if(!$db) {
	print("no database!");
	echo $db->lastErrorMsg();
	$db->close();
	exit();
}
$ret = $db->query("select * from tokens where is_active = 1");
$found = 0;
while($row = $ret->fetchArray(SQLITE3_ASSOC)){
  $datestring=date_format(date_create($row['started']),"d.m.Y");
  print("<h1> Schade!</h1>
Leider hat schon jemand Anderes die Jagd nach dem Zauberbuch am $datestring begonnen<br/>
(bzw. wir haben noch nicht alles für die Schatzsuche zurückgeräumt...).<br/>
Bitte versuch es doch wann anders noch mal!");
	$db->close();
	$headers = "From: $from\r\n";
	mail($receiver, $subject_fail, $text_fail, $headers);
	exit();
}
	print("<h1>Hallo!</h1>");
        // $token = substr(sha1(mt_rand(50, 90000) . 'schwanenbruecke'),10,5);
        //$token = substr(sha1(mt_rand(50, 90000) . 'schwanenbruecke'),10,5);
        $token='T84X!PPX';
	$headers = "From: $from\r\n";
	mail($receiver, $subject_success, $text_success . "Der Token ist: $token\n\n", $headers);
	$db->exec("insert into tokens (name, started, is_active) values ('$token',datetime('now','localtime'), 1)");

	print("Schön, dass du Bibliophilus Magicus helfen willst, sein Zauberbuch zu finden.<br/>
	Kopiere oder notiere dir JETZT diesen Code (diese Webseite gibt den Code nur einmal aus!):
	<h2>$token</h2> <br/>
	Trage den Code in der Actionbound-App ein, um die Jagd nach dem Zauberbuch zu starten. <br/>
	Bis gleich!<br/>
        <img src=\"BibliophilusDaumenHochGanzerHut.png\" alt=\"Bibliophilus Magicus zeigt Daumen Hoch\"/>
        ");

?>
</body>
</html>

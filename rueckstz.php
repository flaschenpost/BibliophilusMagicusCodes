<?php
print("<h1>hallo Admin!</h1>");
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
$userform=<<<EOF
<br/>
<form method="post">
<h3>Alle aktiven Tokens beenden?</h3>
Nutzername: <input type="text" name="user"/> <br/>
Password: <input type="password" name="pass"/><br/>
<input type="submit" value="OK"/>
EOF;

if(! $_POST['user']){
	print($userform);
}
else{
	$found = false;
	$ret = $db->query("select * from users");
	while($row = $ret->fetchArray(SQLITE3_ASSOC)){
		if($row['name'] == $_POST['user'] && $row['passwd'] == $_POST['pass']){
			$found = true;
		}
	}
	if($found){
		$ret = $db->query("update tokens set ended=datetime('now'), is_active=0  where is_active=1");
		print("<h3>alle tokens auf beendet gesetzt!</h3>");
	}
	else{
		print("<h3>Fehler!</h3>Admin {$_POST['user']} konnte nicht verifiziert werden!<br/>");
		print($userform);
	}
}

$ret = $db->query("select * from tokens order by started desc limit 50");
print('<table border="1">');
print("<tr><th>Token</th><th>Start</th><th>Ende</th><th>Aktiv?</th></tr>");
while($row = $ret->fetchArray(SQLITE3_ASSOC)){
	print("<tr><td>{$row['name']}</td><td>{$row['started']}</td><td>{$row['ended']}</td><td>{$row['is_active']}</td></tr>");
}
print("</table>");


?>

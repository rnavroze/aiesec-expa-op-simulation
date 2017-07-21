<?php
error_reporting(E_ALL);

$page_title = "Scoreboard &middot; XPDF";
require_once "lib/init.php";
require_once "lib/library.inc.php";
require_once "head.php";

echo "<table align='center' class='formstable'><tr><th>Team Codename</th><th>Team Name</th><th>Score</th>";
foreach ($teams as $team)
	echo "<tr><td>{$team['tcodename']}</td><td>{$team['tname']}</td><td>{$team['tscore']}</td></tr>";

echo "</table>";
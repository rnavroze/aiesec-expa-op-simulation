<?php
error_reporting(E_ALL);

$page_title = "OP &middot; XPDF";
$is_op = true;
require_once "lib/init.php";
require_once "lib/library.inc.php";
require_once "head.php";

if (!isset($_GET['team']))
{
	echo "<form class='container' action='".defined('CAN_MODIFY' ? 'modify.php' : 'showforms.php')."' method='get'>Team: <select name='team'>";
	foreach ($teams as $key => $team) echo "<option value='$key'>{$team['tcodename']}</option>";
	echo "</select><input type='submit'></form>";
}
else
{
	sanitize($_GET['team']);
	$tid = $_GET['team'];
	$tns = array_select_key(fetch_result(run_query("SELECT * FROM xpdf_orgs WHERE oraisedby = $tid AND omatchedby = 0")), "oid");
	$eps = array_select_key(fetch_result(run_query("SELECT * FROM xpdf_eps WHERE eraisedby = $tid AND ematchedby = 0")), "eid");
	echo "<table align='center' class='formstable'><tr><th>Title</th><th>Product</th><th>Field</th><th>Salary</th><th>Entity Preferences</th></tr>";

	foreach ($tns as $tn)
	{
		// $startdate = date("d/m/y", strtotime($tn['ostartdate']));
		// $enddate = date("d/m/y", strtotime($tn['ostartdate'] . ($tn['oprog'] == 1 ? " +2 years" : " +6 months")));
		// echo "<tr><td><a href='javascript:setField(\"oid\", {$tn['oid']});'>{$tn['oname']}</a></td><td>".($progs[$tn['oprog']])."</td><td>".($fields[$tn['ofield']])."</td><td>Rs. ".number_format($tn['osalary'])."</td><td>$startdate - $enddate</td></tr>";
		$countryprefs = json_decode($tn['ocountryprefs']);
		if (!empty($countryprefs))
			foreach ($countryprefs as &$value)
				$value = $teams[$value]['tcodename'];

		$countryprefs = @implode(", ", $countryprefs);
		echo "<tr><td><a href='javascript:setField(\"oid\", {$tn['oid']});'>{$tn['oname']}</a></td><td>".($progs[$tn['oprog']])."</td><td>".($fields[$tn['ofield']])."</td><td>Rs. ".number_format($tn['osalary'])."</td><td>$countryprefs</td></tr>";
	}
	echo "</table>";
	echo "<br><form action='".(defined('CAN_MODIFY') ? 'modform.php' : 'matchform.php')."' method='get' class='bigform textcenter'><b>Opportunity ID to Apply for</b>: <input type='text' name='oid' id='oid' style='width: 40px;' readonly> (Click on an opportunity to set this)<br>";
	if (!defined('CAN_MODIFY'))
	{
		echo "<b>Which team's people want to apply to this opportunity?</b> <select name='matchwith'>";
		foreach ($teams as $key => $team) echo "<option value='$key'>{$team['tcodename']}</option>";
		echo "</select>";
	}
	else
		echo "<input type='hidden' name='ftype' value='o'>";
	echo "<input type='hidden' name='matchby' value='{$tid}'><br><input type='submit'></form><br><br><br>";

	if (!defined('CAN_MODIFY'))
		die();

	echo "<table align='center' class='formstable'><tr><th>EP Name</th><th>Product</th><th>Field</th><th>Entity Preferences</th>";
	foreach ($eps as $ep)
	{
		$countryprefs = json_decode($ep['ecountryprefs']);
		if (!empty($countryprefs))
			foreach ($countryprefs as &$value)
				$value = $teams[$value]['tcodename'];

		$countryprefs = @implode(", ", $countryprefs);
		echo "<tr><td><a href='javascript:setField(\"eid\", {$ep['eid']});'>{$ep['ename']}</a></td><td>".($progs[$ep['eprog']])."</td><td>".($fields[$ep['efield']])."</td><td>$countryprefs</td></tr>";
	}
	echo "</table>";

	echo "<br><form action='".(defined('CAN_MODIFY') ? 'modform.php' : 'matchform.php')."' method='get' class='bigform textcenter'><b>Person ID who will Apply</b>: <input type='text' name='eid' id='eid' style='width: 40px;' readonly> (Click on a person to set this)<br>";
	if (!defined('CAN_MODIFY'))
	{
		echo "<b>Which team's opportunity does this person want to Apply to?</b> <select name='matchby'>";
		foreach ($teams as $key => $team) echo "<option value='$key'>{$team['tcodename']}</option>";
		echo "</select>";
	}
	else
		echo "<input type='hidden' name='ftype' value='e'>";
	echo "<input type='hidden' name='matchwith' value='{$tid}'><br><input type='submit'></form>";
}
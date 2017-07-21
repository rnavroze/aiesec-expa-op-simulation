<?php
error_reporting(E_ALL);

$is_op = true;
$page_title = "OP &middot; XPDF";
require_once "lib/init.php";
require_once "lib/library.inc.php";
require_once "head.php";

foreach (['matchwith', 'matchby'] as $post_test)
		if (!isset($_GET[$post_test]) || empty($_GET[$post_test]))
			field_not_filled($post_test);

if (isset($_GET['oid']))
	$type = 2;
else
	$type = 1;


if ($_GET['matchby'] == $_GET['matchwith'])
	die("You cannot match with your own team.");

sanitize($_GET['matchwith']);
sanitize($_GET['matchby']);
@sanitize($_GET['oid']);
@sanitize($_GET['eid']);

if ($type == 1)
	$tns = array_select_key(fetch_result(run_query("SELECT * FROM xpdf_orgs WHERE oraisedby = {$_GET['matchby']} AND omatchedby = 0")), "oid");
else
	$eps =  array_select_key(fetch_result(run_query("SELECT * FROM xpdf_eps WHERE eraisedby = {$_GET['matchwith']} AND ematchedby = 0")), "eid");

if ($type == 2)
{
	echo "<table align='center' class='formstable'><tr><th>EP Name</th><th>Product</th><th>Field</th><th>Entity Preferences</th>";
	foreach ($eps as $ep)
	{
		// $startdate = date("d/m/y", strtotime($ep['estartdate']));
		// $enddate = date("d/m/y", strtotime($ep['estartdate'] . ($ep['eprog'] == 1 ? " +1 month" : " +1 week")));
		// echo "<tr><td><a href='javascript:setField(\"eid\", {$ep['eid']});'>{$ep['ename']}</a></td><td>".($progs[$ep['eprog']])."</td><td>".($fields[$ep['efield']])."</td><td>$startdate - $enddate</td></tr>";

		$countryprefs = json_decode($ep['ecountryprefs']);
		if (!empty($countryprefs))
			foreach ($countryprefs as &$value)
				$value = $teams[$value]['tcodename'];

		$countryprefs = @implode(", ", $countryprefs);
		echo "<tr><td><a href='javascript:setField(\"eid\", {$ep['eid']});'>{$ep['ename']}</a></td><td>".($progs[$ep['eprog']])."</td><td>".($fields[$ep['efield']])."</td><td>$countryprefs</td></tr>";
	}
	echo "</table>";

	echo "<br><form action='submit.php' method='post' class='bigform textcenter'><b>Which person should apply</b>? <input type='text' name='eid' id='eid' style='width: 40px;' readonly> (Click on a person to set this)";
	echo "<input type='hidden' name='oid' value='{$_GET['oid']}'><input type='hidden' name='matchwith' value='{$_GET['matchwith']}'><input type='hidden' name='matchby' value='{$_GET['matchby']}'><input type='hidden' name='act' value='match'><br><input type='submit'></form>";
}
else
{
	echo "<table align='center' class='formstable'><tr><th>Title</th><th>Product</th><th>Field</th><th>Salary</th><th>Entity Preferences</th></tr>";

	foreach ($tns as $tn)
	{
		$countryprefs = json_decode($tn['ocountryprefs']);
		if (!empty($countryprefs))
			foreach ($countryprefs as &$value)
				$value = $teams[$value]['tcodename'];

		$countryprefs = @implode(", ", $countryprefs);
		echo "<tr><td><a href='javascript:setField(\"oid\", {$tn['oid']});'>{$tn['oname']}</a></td><td>".($progs[$tn['oprog']])."</td><td>".($fields[$tn['ofield']])."</td><td>Rs. ".number_format($tn['osalary'])."</td><td>$countryprefs</td></tr>";
	}
	echo "</table>";

	echo "<br><form action='submit.php' method='post' class='bigform textcenter'><b>Which form to apply to</b>? <input type='text' name='oid' id='oid' style='width: 40px;' readonly> (Click on an organization to set this)";
	echo "<input type='hidden' name='eid' value='{$_GET['eid']}'><input type='hidden' name='matchwith' value='{$_GET['matchwith']}'><input type='hidden' name='matchby' value='{$_GET['matchby']}'><input type='hidden' name='act' value='match'><br><input type='submit'></form>";
}
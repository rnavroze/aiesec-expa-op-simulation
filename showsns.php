<?php
error_reporting(E_ALL);

$page_title = "Value Delivery &middot; XPDF";
require_once "lib/init.php";
require_once "lib/library.inc.php";
require_once "head.php";


if (!isset($_GET['team']))
{
	echo "<form class='container' action='showsns.php' method='get'>Team: <select name='team'>";
	foreach ($teams as $key => $team) echo "<option value='$key'>{$team['tcodename']}</option>";
	echo "</select><input type='submit'></form>";
}
else
{
	sanitize($_GET['team']);
	$tid = $_GET['team'];
	$tns = array_select_key(fetch_result(run_query("SELECT *, xpdf_eps.* FROM xpdf_orgs LEFT JOIN xpdf_eps ON omatchedto = eid WHERE oraisedby = $tid AND omatchedby <> 0 AND osns = '{}'")), "oid");
	$eps = array_select_key(fetch_result(run_query("SELECT *, xpdf_orgs.* FROM xpdf_eps LEFT JOIN xpdf_orgs ON ematchedto = oid WHERE ematchedby = $tid AND ematchedby <> 0 AND esns = '{}'")), "eid");

	echo "<table align='center' class='formstable'><tr><th>Title</th><th>Product</th><th>Field</th><th>Opened By</th><th>Approved EP</th></tr>";
	foreach ($tns as $tn)
	{
		echo "<tr><td><a href='realize.php?ftype=o&id={$tn['oid']}'>{$tn['oname']}</a></td><td>".($progs[$tn['oprog']])."</td><td>".($fields[$tn['ofield']])."</td><td>".$teams[$tn['oraisedby']]['tcodename']."</td><td>{$tn['ename']}</td></tr>";
	}
	echo "</table>";
	echo "<br><br><br><br>";

	echo "<table align='center' class='formstable'><tr><th>EP Name</th><th>Product</th><th>Field</th><th>Approved By</th><th>Approval To</th>";
	foreach ($eps as $ep)
	{
		echo "<tr><td><a href='realize.php?ftype=e&id={$ep['eid']}'>{$ep['ename']}</a></td><td>".($progs[$ep['eprog']])."</td><td>".($fields[$ep['efield']])."</td><td>".$teams[$ep['ematchedby']]['tcodename']."</td><td>{$ep['oname']}</td></tr>";
	}
	echo "</table>";
}

// if (!isset($_GET['facinumber']))
// {
// 	echo "<form class='container' action='showsns.php' method='get'>Faci Number: <input type='number' name='facinumber'><input type='submit'></form>";
// }
// else
// {
// 	sanitize($_GET['facinumber']);
// 	$faci = $_GET['facinumber'];
// 	$eps = fetch_result(run_query("SELECT * FROM xpdf_eps WHERE efacinumber = $faci AND ematchedby <> 0 AND esns = '{}'"));
// 	$tns = fetch_result(run_query("SELECT * FROM xpdf_orgs WHERE ofacinumber = $faci AND omatchedby <> 0 AND osns = '{}'"));

// 	if (empty($tns))
// 	{
// 		$forms = $eps;
// 		$ftype = 'e';
// 	}
// 	else
// 	{
// 		$forms = $tns;
// 		$ftype = 'o';
// 	}

// 	echo "<div class='container textcenter'><b>My Matches</b><br><br>";
// 	echo "<table align='center' class='formstable'><tr><th>Title</th><th>Product</th><th>Field</th><th>Opened By</th><th>Approved By</th></tr>";
// 	foreach ($forms as $form)
// 		echo "<tr><td><a href='realize.php?ftype=$ftype&id=".$form[$ftype.'id']."&faci=$faci'>".$form[$ftype.'name']."</a></td><td>".($progs[$form[$ftype.'prog']])."</td><td>".($fields[$form[$ftype.'field']])."</td><td>".$teams[$form[$ftype.'raisedby']]['tcodename']."</td><td>".$teams[$form[$ftype.'matchedby']]['tcodename']."</td></tr>";
	
// 	echo "</table>";
// }
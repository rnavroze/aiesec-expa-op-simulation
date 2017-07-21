<?php
error_reporting(E_ALL);

require_once "lib/init.php";
require_once "lib/library.inc.php";
require_once "head.php";
?>
<div class="container">
<?php
foreach ($_POST as &$post)
	if (!is_array($post))
		sanitize($post);

$act = $_POST['act'];

if ($act == 'addform')
{
	if (NO_COUNTRY_PREFS)
		$post_test_array = ['type', 'prog', 'field', 'raisedby', 'facinumber'];
	else
		$post_test_array = ['type', 'prog', 'field', 'countrypref', 'raisedby', 'facinumber'];
	foreach ($post_test_array as $post_test)
		if (!isset($_POST[$post_test]) || empty($_POST[$post_test]))
			field_not_filled($post_test);

	$type = $_POST['type'];
	$prog = $_POST['prog'];

	if ($type == "ep")
	{
		if (empty($_POST["ename_$prog"]))
			field_not_filled('name');

		foreach ($_POST["ename_$prog"] as $ep)
		{
			$ep = explode(".", $ep);
			$epname = $db->escape_string($ep[1]);
			$epfield = $db->escape_string($ep[0]);
			insert_query([
				'ename' => $epname,
				'eprog' => $prog == "gip" ? 1 : 2,
				'efield' => $epfield,
				'eraisedby' => $_POST['raisedby'],
				'ematchedby' => 0,
				'ematchedto' => 0,
				'eaddtime' => date("H:i:s"),
				'estartdate' => '2016-01-01',
				'esns' => '{}',
				'ecountryprefs' => NO_COUNTRY_PREFS ? '' : json_encode($_POST['countrypref']),
				'efacinumber' => $_POST['facinumber']
			], "xpdf_eps");
		}
	}
	else if ($type == "tn")
	{
		for ($n = 1; $n <= $_POST['noforms']; $n++)
			insert_query([
				'oname' => ($prog == "gip" ? $_POST['oname'] : $_POST['nname']) . ($n > 1 ? " $n" : ""),
				'oprog' => $prog == "gip" ? 1 : 2,
				'ofield' => $_POST['field'],
				'osalary' => $prog == "gip" ? $_POST['salary'] : 0,
				'oraisedby' => $_POST['raisedby'],
				'omatchedby' => 0,
				'omatchedto' => 0,
				'oaddtime' => date("H:i:s"),
				'ostartdate' => '2016-01-01',
				'ocountryprefs' => NO_COUNTRY_PREFS ? '' : json_encode($_POST['countrypref']),
				'osns' => "{}",
				'ofacinumber' => $_POST['facinumber']
			], "xpdf_orgs");
	}
	insert_query([
		'stime' => date("H:i:s"),
		'saction' => 1,
		'stype' => $type == "ep" ? 2 : 1,
		'sprog' => $prog == "gip" ? 1 : 2,
		'steam' => $_POST['raisedby']
	], "xpdf_stats");
	// TODO: figure out IR
	run_query("UPDATE xpdf_teams SET tscore = tscore +10 WHERE tid = {$_POST['raisedby']}");
	if (!NO_COUNTRY_PREFS)
		if (array_intersect($_POST['countrypref'], json_decode($teams[$_POST['raisedby']]['tname'])))
			run_query("UPDATE xpdf_teams SET tscore = tscore +10 WHERE tid = {$_POST['raisedby']}");

	echo ($type == "tn" ? "The form(s) has/have been added to system" : "The user has registered on OP") . " successfully. DO NOT REFRESH THIS PAGE. <a href='addform.php'>Add Next</a>";
}
else if ($act == 'match')
{
	foreach (['oid', 'eid', 'matchwith', 'matchby'] as $post_test)
		if (!isset($_POST[$post_test]) || empty($_POST[$post_test]))
			field_not_filled($post_test);

	// Check eligibility
	$tn = fetch_one(run_query("SELECT * FROM xpdf_orgs WHERE oid = {$_POST['oid']} AND omatchedto = 0"));
	$ep = fetch_one(run_query("SELECT * FROM xpdf_eps WHERE eid = {$_POST['eid']} AND ematchedto = 0"));

	if (empty($tn) || empty($ep))
		die("Something went wrong. Contact the Digimon of the conference (Raihan - South, Shail/Sahej - North).");

	$canmatch = 0;
	$matched = 0;
	$match_error = "";
	$breakprobability = mt_rand(0, 100);
	// $tn_dates = [$tn['ostartdate'], strtotime($tn['ostartdate'] . ($tn['oprog'] == 1 ? " +2 years" : " +6 months"))];
	// $ep_dates = [$ep['estartdate'], strtotime($ep['estartdate'] . ($ep['eprog'] == 1 ? " +1 month" : " +1 week"))];

	// if (!($tn_dates[0] <= $ep_dates[0] && $tn_dates[1] >= $ep_dates[1]))
	// 	$match_error .= "{$ep['ename']} is not available for the full period of internship. ";

	if (!NO_COUNTRY_PREFS)
	{
		if (null == $ep['ecountryprefs'] || !in_array($_POST['matchby'], json_decode($ep['ecountryprefs'])))
			$match_error .= "<div class='matchmsg'>{$ep['ename']} does not want to get matched to the selected entity.</div>";

		if (null == $tn['ocountryprefs'] || !in_array($_POST['matchwith'], json_decode($tn['ocountryprefs'])))
			$match_error .= "<div class='matchmsg'>{$tn['oname']} does not want interns from the selected entity.</div>";
	}

	if ($tn['oprog'] != $ep['eprog'])
		$match_error .= "<div class='matchmsg'>{$ep['ename']} does not match the product.</div>";

	if ($tn['ofield'] != $ep['efield'])
		$match_error .= "<div class='matchmsg'>{$ep['ename']} is not interested in this field.</div>";

	if ($match_error == "")
		$canmatch = 1;
	else
		$match_error .= "<div class='matchmsg'><br><br><br><a href='showforms.php'>Go back to nOPe</a></div>";

	if ($canmatch)
	{
		if ($breakprobability > 90)
		{
			$error_reasons = ["%s's college did not grant permission to go for the internship.", "%s's parents changed their mind at the last minute.", "Person lost interest and decided not to approve the internship.", "%s is facing some financial issues and is unable to take up the internship.", "%'s CV was rejected by %s.", '%2$s backed out at the last minute.', '%2$s is not receiving calls.'];
			$canmatch = -1;
			$reason = rand(0, count($error_reasons)-1);
			$match_error = "<div class='matchmsg'>" . sprintf($error_reasons[$reason], $ep['ename'], $tn['oname']) . "<br><br><br><a href='showforms.php'>Go back to nOPe</a></div>";
		}
		else
		{
			// This is where the match actually happens
			update_query([
				'omatchedby' => $_POST['matchwith'],
				'omatchedto' => $_POST['eid']
			], "xpdf_orgs", "oid = {$_POST['oid']}");
			update_query([
				'ematchedby' => $_POST['matchby'],
				'ematchedto' => $_POST['oid']
			], "xpdf_eps", "eid = {$_POST['eid']}");
			
			run_query("UPDATE xpdf_teams SET tscore = tscore +10 WHERE tid = {$_POST['matchwith']}");
			run_query("UPDATE xpdf_teams SET tscore = tscore +10 WHERE tid = {$_POST['matchby']}");
			// Figure out IRs
			if (!NO_COUNTRY_PREFS)
				if (array_intersect(json_decode($teams[$_POST['matchwith']]['tname']), json_decode($teams[$_POST['matchby']]['tname'])))
				{
					run_query("UPDATE xpdf_teams SET tscore = tscore +10 WHERE tid = {$_POST['matchwith']}");
					run_query("UPDATE xpdf_teams SET tscore = tscore +10 WHERE tid = {$_POST['matchby']}");
				}

			insert_query([
				'stime' => date("H:i:s"),
				'saction' => 2,
				'stype' => 2,
				'sprog' => $ep['eprog'],
				'steam' => $_POST['matchwith']
			], "xpdf_stats");
			insert_query([
				'stime' => date("H:i:s"),
				'saction' => 2,
				'stype' => 1,
				'sprog' => $tn['oprog'],
				'steam' => $_POST['matchby']
			], "xpdf_stats");
			$matched = 1;
		}
	}
	else
	{
		if ($breakprobability > 80)
		{
			$error_reasons = ['%2$s has decided to terminate the contract due to constantly being provided unqualified people.', '%s has decided to terminate the contract due to constantly being provided unqualified internships.'];
			$reason = rand(0, count($error_reasons)-1);
			$match_error .= '<div class="matchmsg">' . sprintf($error_reasons[$reason], $ep['ename'], $tn['oname']) . "</div>";
			/*if ($reason == 0)
				run_query("DELETE FROM xpdf_orgs WHERE oid = {$_POST['oid']}");
			else if ($reason == 1)
				run_query("DELETE FROM xpdf_eps WHERE eid = {$_POST['eid']}");*/
				echo "Forms are not being deleted during the test.";
		}
	}
	echo "<table border=0><tr><td width='100%'><div class='textcenter'><div class='matchmsg'>{$ep['ename']} is applying for {$tn['oname']}...</div>";
	if ($matched == 1)
		echo "<div class='matchmsg'><b>Applied</b> successfully!<br><br></div><div class='matchmsg'>Waiting for interview...</div><div class='matchmsg'>Interview conducted!<br><br></div><div class='matchmsg'>{$ep['ename']} is now <b>Accepted</b>. Congratulations!<br><br></div><div class='matchmsg'>Waiting for AN to be signed and final approval...</div><div class='matchmsg'>All formalities have been completed.<br><br><b>{$ep['ename']} has been Approved</b>.<br><br><br><a href='showforms.php'>Go back to nOPe</a></div>";
	else
	{
		if ($canmatch == -1)
		{
			echo "<div class='matchmsg'><b>Applied</b> successfully!<br><br></div><div class='matchmsg'>Waiting for interview...</div><div class='matchmsg'>Interview conducted!<br><br></div><div class='matchmsg'>{$ep['ename']} is now <b>Accepted</b>. Congratulations!<br><br></div><div class='matchmsg'>Waiting for AN and final approval...</div>$match_error<div class='matchmsg'><br><br><b>Approval was not successful.</b></div>";
		}
		else
			echo "<div class='matchmsg' data-delay='2500'>$match_error</div>";
	}
	echo "</td><td valign='top'><div class='loader' style='float: left;'><img src='res/loader" . mt_rand(1,6) . ".gif'></div></td></tr></table>";
	echo <<<SCRIPT
<script>
var delay = 0;
$('.matchmsg')
	.each(function(thing)
	{ 
		if ($(this).data("delay"))
			delay += $(this).data("delay");
		else
			delay += 800 + Math.random()*1600;

		$(this)
			.delay(delay)
			.queue(function() 
			{ 
				$(this)
				.removeClass('matchmsg')
				.dequeue(); 
			}); 
	})
	.promise()
	.done(function() 
	{ 
		$(".loader").hide();
	});
</script>
SCRIPT;
}
else if ($act == 'realize')
{
	foreach (['nps', 'ftype', 'id', 'prog', 'raisedby'] as $post_test)
		if (!isset($_POST[$post_test]) || empty($_POST[$post_test]))
			field_not_filled($post_test);

	if ($_POST['ftype'] == 'o')
	{
		$done_deal = fetch_one(run_query("SELECT osns FROM xpdf_orgs WHERE oid = {$_POST['id']}"));
		if (empty($done_deal) || $done_deal['osns'] != '{}')
			die("Something went wrong.");
		
		update_query([
			'osns' => $_POST['nps']
		], "xpdf_orgs", "oid = {$_POST['id']}");
	}
	else if ($_POST['ftype'] == 'e')
	{
		$done_deal = fetch_one(run_query("SELECT esns FROM xpdf_eps WHERE eid = {$_POST['id']}"));
		if (empty($done_deal) || $done_deal['esns'] != '{}')
			die("Something went wrong.");

		update_query([
			'esns' => $_POST['nps']
		], "xpdf_eps", "eid = {$_POST['id']}");
	}

	insert_query([
		'stime' => date("H:i:s"),
		'saction' => 3,
		'stype' => $_POST['ftype'] == 'o' ? 1 : 2,
		'sprog' => $_POST['prog'],
		'steam' => $_POST['raisedby']
	], "xpdf_stats");

	$scoremod = "";
	if ($_POST['nps'] == 10)
		$scoremod = "+20";
	else if ($_POST['nps'] == 1)
		$scoremod = "-5";
	else
		$scoremod = "+{$_POST['nps']}";
	run_query("UPDATE xpdf_teams SET tscore = tscore $scoremod WHERE tid = {$_POST['raisedby']}");

	echo "Updated NPS. <a href='showsns.php'>Back to SNS</a>";
}
else if ($act == 'modform')
{
	$ftype = $_POST['ftype'];
	$cp = $_POST['countrypref'];
	update_query([
		$ftype . 'countryprefs' => json_encode($cp)
	], $ftype == 'o' ? 'xpdf_orgs' : 'xpdf_eps', "{$ftype}id = {$_POST['id']}");
	echo "Done. <a href='modify.php'>Modify More</a>";
}

?>
<?php
error_reporting(E_ALL);

$page_title = "Faci Section &middot; XPDF";
require_once "lib/init.php";
require_once "lib/library.inc.php";
require_once "head.php";

$sns = [
	1 => "VISA and Work Permit",
	2 => "Arrival Pickup",
	3 => "Departure Support",
	4 => "Job Description",
	5 => "Duration",
	6 => "Working Hour",
	7 => "First Day of Work",
	8 => "Individual Goals",
	9 => "Insurance",
	10 => "Accommodation",
	11 => "Basic Living Costs",
	12 => "AIESEC Purpose",
	13 => "Expectations Settings",
	14 => "Preparation",
	15 => "SE Learning",
	16 => "HE Learning"
];
$problems_ogx = [
	"Pickup was not provided at the airport.",
	"Job description does not match.",
	"Accommodation has no electricity.",
	"Living costs are much higher than expected.",
	"Accommodation is too far from office.",
	"Office is not in a condition to conduct work.",
	"SE/HE are not picking up calls when stranded at the airport.",
	"Washing machine is not working in the house.",
	"Cabs in the entity do not have ACs.",
	"Job was unfairly terminated."
];
$problems_icx = [
	"[Organization] Intern does not have the required qualifications.",
	"[Organization] Intern is not showing up at office.",
	"[Organization] Intern is not up to spec.",
	"[Organization] No response from AIESEC entity.",
	"[Organization] Intern is slacking off on the job.",
	"[Intern] Pickup was not provided at the airport.",
	"[Intern] Job description does not match.",
	"[Intern] Accommodation has no electricity.",
	"[Intern] Living costs are much higher than expected.",
	"[Intern] Accommodation is too far from office.",
	"[Intern] Office is not in a condition to conduct work.",
];

foreach (['ftype', 'id'] as $post_test)
		if (!isset($_GET[$post_test]) || empty($_GET[$post_test]))
			field_not_filled($post_test);

sanitize($_GET['id']);
sanitize($_GET['ftype']);

if ($_GET['ftype'] == 'o')
	$form = fetch_one(run_query("SELECT * FROM xpdf_orgs WHERE oid = {$_GET['id']}"));
else
	$form = fetch_one(run_query("SELECT * FROM xpdf_eps WHERE eid = {$_GET['id']}"));

$sns2 = array_values($_GET['ftype'] == 'o' ? $problems_icx : $problems_ogx);
shuffle($sns2);
echo "<div class='container textcenter'><b>Problems Faced</b><br>";
for ($i = 1; $i <= 1; $i++)
	echo $sns2[$i]."<br>";

echo "<br><form action='submit.php' method='post' class='bigform textcenter'><b>NPS</b>? <input type='number' name='nps' min='1' max='10' value='8'>";
echo "<input type='hidden' name='ftype' value='{$_GET['ftype']}'><input type='hidden' name='prog' value='".$form[$_GET['ftype']."prog"]."'><input type='hidden' name='raisedby' value='".($_GET['ftype'] == 'o' ? $form["oraisedby"] : $form['ematchedby'])."'><input type='hidden' name='id' value='{$_GET['id']}'><input type='hidden' name='act' value='realize'><br><input type='submit'></form>";
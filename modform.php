<?php
error_reporting(E_ALL);

$page_title = "Modify Form &middot; XPDF";
require_once "lib/init.php";
require_once "lib/library.inc.php";
require_once "head.php";

foreach (['ftype'] as $post_test)
		if (!isset($_GET[$post_test]) || empty($_GET[$post_test]))
			field_not_filled($post_test);

@sanitize($_GET['oid']);
@sanitize($_GET['eid']);

if ($_GET['ftype'] == 'o')
{
	$id = $_GET['oid'];
	$form = fetch_one(run_query("SELECT * FROM xpdf_orgs WHERE oid = {$_GET['oid']}"));
}
else
{
	$id = $_GET['eid'];
	$form = fetch_one(run_query("SELECT * FROM xpdf_eps WHERE eid = {$_GET['eid']}"));
}

$country_prefs = json_decode($form[$_GET['ftype'] . 'countryprefs']);
echo "<form action='submit.php' method='post' class='bigform textcenter'>";
foreach ($teams as $tid => $team) echo "<nobr><input type='checkbox' name='countrypref[]' value='$tid' id='team$tid'".(in_array($tid, $country_prefs) ? 'checked' : '')."> <label for='team$tid'>{$team['tcodename']}</label></nobr> &nbsp;";

echo "<br><input type='hidden' name='ftype' value='{$_GET['ftype']}'><input type='hidden' name='id' value='$id'><input type='hidden' name='act' value='modform'><br><input type='submit'></form>";
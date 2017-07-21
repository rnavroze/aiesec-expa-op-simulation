<?php
$types = [ 1 => "ICX", 2 => "OGX" ];
$fields = [ 1 => "Sales", 2 => "Marketing", 3 => "IT", 4 => "Education", 5 => "Women Empowerment", 6 => "Child Rights" ];
$progs = [ 1 => "GT", 2 => "GV" ];
$teams = array_select_key(fetch_result(run_query("SELECT * FROM xpdf_teams")), "tid");
define("NO_COUNTRY_PREFS", false);
function print_r2($var)
{
	echo "<pre style='font-size: 12px;'>".print_r($var, 1)."</pre>";
}
function field_not_filled($field)
{
	die("One of the following fields have not been filled: $field. Please press the back button on your browser and check the form.");
}

?>
<!DOCTYPE HTML>
<html>
<head>
	<title><?=(isset($page_title) ? $page_title : "XPDF")?></title>
	<script>
	function setField(fieldname, value)
	{
		$("#" + fieldname).val(value);
	}
	</script>
	<script src="lib/jquery.min.js"></script>
	<style>
	/* latin */
	@font-face {
	  font-family: 'Lato';
	  font-style: normal;
	  font-weight: 400;
	  src: local('Lato Regular'), local('Lato-Regular'), url('res/lato/Lato-Regular.ttf') format('ttf');
	  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
	}
	@font-face {
	  font-family: 'Lato';
	  font-style: normal;
	  font-weight: 700;
	  src: local('Lato Bold'), local('Lato-Bold'), url('res/lato/Lato-Bold.ttf.ttf') format('ttf');
	  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
	}
	@font-face {
	  font-family: 'Lato';
	  font-style: normal;
	  font-weight: 200;
	  src: local('Lato Light'), local('Lato-Light'), url('res/lato/Lato-Light.ttf.ttf') format('ttf');
	  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
	}
	html, body
	{
		padding: 0;
		margin: 0;
		overflow-x: hidden;
		font-family: Lato, Century Gothic, Arial, sans-serif;
		font-size: 18px;
	}
	.header
	{
		font-family: Lato;
		background-color: #037ef3;
		color: #fff;
		font-size: 3em;
		width: 100vw;
		text-align: center;
		padding: 8px;
		margin: 0;
	}
	.header > a
	{
		text-decoration: none;
		color: #fff;
	}
	.container
	{
		width: 80%;
		margin: auto;
	}
	label
	{
		cursor: pointer;
	}
	.bigform input, .bigform option, .bigform select
	{
		font-size: 24px;
		margin: 2px 0;
	}
	.gip, .gcdp, .icx, .ogx
	{
		display: none;
	}
	.formstable
	{
		border-collapse: collapse;
	}
	.formstable td
	{
		padding: 8px;
		border: 1px solid #000;
	}
	.center
	{
		margin: auto;
	}
	.textcenter
	{
		text-align: center;
	}
	.matchmsg
	{
		display: none;
	}
	</style>
</head>
<body>
<div class="header"><a href="index.php"><?=(isset($is_op) ? "nOPe" : "FaXePA")?></a></div>
<br>
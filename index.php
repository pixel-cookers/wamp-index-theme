<?php

//chemin jusqu'au fichier de conf de WampServer
$wampConfFile = '../wampmanager.conf';

//chemin jusqu'aux fichiers alias
$aliasDir = '../alias/';

// on charge le fichier de conf locale
if (!is_file($wampConfFile))
    die ('Unable to open WampServer\'s config file, please change path in index.php file');
//require $wampConfFile;
$fp = fopen($wampConfFile,'r');
$wampConfFileContents = fread ($fp, filesize ($wampConfFile));
fclose ($fp);


//on rs les versions des applis
preg_match('|phpVersion = (.*)\n|',$wampConfFileContents,$result);
$phpVersion = str_replace('"','',$result[1]);
preg_match('|apacheVersion = (.*)\n|',$wampConfFileContents,$result);
$apacheVersion = str_replace('"','',$result[1]);
preg_match('|mysqlVersion = (.*)\n|',$wampConfFileContents,$result);
$mysqlVersion = str_replace('"','',$result[1]);
preg_match('|wampserverVersion = (.*)\n|',$wampConfFileContents,$result);
$wampserverVersion = str_replace('"','',$result[1]);



// repertoires  gnorer dans les projets
$projectsListIgnore = array ('.','..');


// textes
$langues = array(
	'en' => array(
		'langue' => 'English',
		'autreLangue' => 'Version Fran&ccedil;aise',
		'autreLangueLien' => 'fr',
		'titreHtml' => 'WAMPSERVER Homepage',
		'titreConf' => 'Server Configuration',
		'versa' => 'Apache Version :',
		'versp' => 'PHP Version :',
		'versm' => 'MySQL Version :',
		'phpExt' => 'Loaded Extensions : ',
		'titrePage' => 'Tools',
		'txtProjet' => 'Your Projects',
		'txtNoProjet' => 'No projects yet.<br />To create a new one, just create a directory in \'www\'.',
		'txtAlias' => 'Your Aliases',
		'txtNoAlias' => 'No Alias yet.<br />To create a new one, use the WAMPSERVER menu.',
		'faq' => 'http://www.en.wampserver.com/faq.php'
	),
	'fr' => array(
		'langue' => 'Fran?s',
		'autreLangue' => 'English Version',
		'autreLangueLien' => 'en',
		'titreHtml' => 'Accueil WAMPSERVER',
		'titreConf' => 'Configuration Serveur',
		'versa' => 'Version de Apache:',
		'versp' => 'Version de PHP:',
		'versm' => 'Version de MySQL:',
		'phpExt' => 'Extensions Charg&eacute;es: ',
		'titrePage' => 'Outils',
		'txtProjet' => 'Vos Projets',
		'txtNoProjet' => 'Aucun projet.<br /> Pour en ajouter un nouveau, cr&eacute;ez simplement un r&eacute;pertoire dans \'www\'.',
		'txtAlias' => 'Vos Alias',
		'txtNoAlias' => 'Aucun alias.<br /> Pour en ajouter un nouveau, utilisez le menu de WAMPSERVER.',
		'faq' => 'http://www.wampserver.com/faq.php'
	)
);



// images
$favicon = <<< EOFILE
iVBORw0KGgoAAAANSUhEUgAAAB8AAAAfCAYAAAAfrhY5AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJ
bWFnZVJlYWR5ccllPAAAA2RpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdp
bj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6
eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEz
NDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJo
dHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlw
dGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEu
MC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVz
b3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1N
Ok9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo1ODg0QkM3NUZBMDhFMDExODkyQ0U2NkE5ODVB
M0Q2OSIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDoxRkI1ODNGRTA5MDMxMUUwQjAwNEEwODc0
OTk5N0ZEOCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDoxRkI1ODNGRDA5MDMxMUUwQjAwNEEw
ODc0OTk5N0ZEOCIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3Mi
PiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo1ODg0QkM3NUZB
MDhFMDExODkyQ0U2NkE5ODVBM0Q2OSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo1ODg0QkM3
NUZBMDhFMDExODkyQ0U2NkE5ODVBM0Q2OSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRG
PiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PiUukzAAAAHHSURBVHja5FfRccIwDLVz
/W+7QdggbJBM0HQCwg+/LRNwTJDymx9ggmYDsgEZwRuUDVI5ET1XyE5CuIa76k7ABVtPluQnRVZV
JcYST4woD85/ZRbC5wxUf/sdbZagBehGVAvlNM+GXWYaaIugQ+QDdA1OnLqByyyAzwPo042iqyMx
BwdKN7jMNODREWKFyonv2KdPPqERoDlPGQMKQ7drPWPjfAy6Inb080/QiK/2Js8JMacBpzWwzGIs
QFdxhujkFMNtSkj3m1ftjTnxEg0f0XNXAYb1mmatwFPSFM1s4NTwuUp18QU9CiyonWj2rhkHWXAK
kNeh7gdMQ5wzRdnKcAo9DwZcsRBtqL70qm7Ior3B/5zbI0IKrvv8mxarhXSsXtrY8m5OfjB+F5SN
BkhKrpi8635uaxAvkO9HpgZSB/v57f2cFpEQzz+UeZ28Yvq+bMXpkb5rSgwLc+Z5Fylwb+y68x4p
MlNW2CLnPUmnrE/d7F1dOGXJ+Qb0neQqre9ptZiAscTI38ng7YTQ8g6Budlg75pktkxPV9idctss
1mGYOKciupsxatQB8pJkmkUTpgCvHZ0jDtg+t4/60vAf3tVGBf8WYAC3Rq8Ub3mHyQAAAABJRU5E
rkJggg==
EOFILE;


//affichage du phpinfo
if (isset($_GET['phpinfo']))
{
	phpinfo();
	exit();
}


//affichage des images
if (isset($_GET['img']))
{
    switch ($_GET['img'])
    {
        case 'favicon' :
        header("Content-type: image/x-icon");
        echo base64_decode($favicon);
        exit();
    }
}



// D?nition de la langue et des textes

if (isset ($_GET['lang']))
{
	$langue = $_GET['lang'];
}
elseif (isset ($_SERVER['HTTP_ACCEPT_LANGUAGE']) AND preg_match("/^fr/", $_SERVER['HTTP_ACCEPT_LANGUAGE']))
{
	$langue = 'fr';
}
else
{
	$langue = 'en';
}

//initialisation
$aliasContents = '';

// recuperation des alias
if (is_dir($aliasDir))
{
    $handle=opendir($aliasDir);
    while ($file = readdir($handle))
    {
	    if (is_file($aliasDir.$file) && strstr($file, '.conf'))
	    {
		    $msg = '';
		    $aliasContents .= '<li><a href="'.str_replace('.conf','',$file).'/"><i class="icon-bookmark"></i> '.str_replace('.conf','',$file).'</a></li>';
	    }
    }
    closedir($handle);
}
if (!isset($aliasContents))
	$aliasContents = $langues[$langue]['txtNoAlias'];


// recuperation des projets
$handle=opendir(".");
$projectContents = '';
while ($file = readdir($handle))
{
	if (is_dir($file) && !in_array($file,$projectsListIgnore))
	{
		$projectContents .= '<li><a href="'.$file.'"><i class="icon-folder-open"></i> '.$file.'</a></li>';
	}
}
closedir($handle);
if (!isset($projectContents))
	$projectContents = $langues[$langue]['txtNoProjet'];


//initialisation
$phpExtContents = '';

// recuperation des extensions PHP
$loaded_extensions = get_loaded_extensions();
foreach ($loaded_extensions as $extension)
	$phpExtContents .= "<li><i class='icon-th-large'></i> ${extension}</li>";




$pageContents = <<< EOPAGE
<?xml version="1.0" encoding="iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html lang="en" xml:lang="en">
<head>
	<title>{$langues[$langue]['titreHtml']}</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<link href="_pwi/css/bootstrap.css" rel="stylesheet">
	<link href="_pwi/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="_pwi/css/docs.css" rel="stylesheet">

	<style>
		.projects a, .aliases a, .tools a {
			display: block;
			font-size: 16px;
			padding: 2px 0 2px 3px;
		}
		.projects a:hover, .aliases a:hover, .tools a:hover {
			background: #222;
			color: #44CCFF;
			text-decoration: none;
		}
	</style>



	<link rel="shortcut icon" href="index.php?img=favicon" type="image/ico" />
</head>

<body style="background: url('_pwi/img/stressed_linen.png')">
	<div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="./index.html">WAMP</a>
          <div class="nav-collapse collapse">
            <ul class="nav pull-right">
              <li class="divider-vertical"></li>
              <li><a href="?lang={$langues[$langue]['autreLangueLien']}">{$langues[$langue]['autreLangue']}</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Version ${wampserverVersion} <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="http://www.wampserver.com">WampServer</a></li>
		          <li><a href="http://www.wampserver.com/en/donations.php">Donate</a></li>
				  <li><a href="http://www.alterway.fr">Alter Way</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
		<div style="float: left; width: 33%">
			<h2>{$langues[$langue]['txtProjet']}</h2>
			<ul class="projects unstyled">
			$projectContents
			</ul>
		</div>
		<div style="float: left; width: 33%">
			<h2>{$langues[$langue]['txtAlias']}</h2>
			<ul class="aliases unstyled">
			${aliasContents}
			</ul>
		</div>
		<div style="float: left; width: 33%">
			<h2>{$langues[$langue]['titrePage']}</h2>
			<ul class="tools unstyled">
				<li><a href="?phpinfo=1"><i class="icon-wrench"></i> phpinfo()</a></li>
				<li><a href="phpmyadmin/"><i class="icon-wrench"></i> phpmyadmin</a></li>
			</ul>
		</div>

		<br clear="all" />

		<h2> {$langues[$langue]['titreConf']} </h2>
		<div class="well">
			<dl class="content" style="margin-top: 0;">
				<dt>{$langues[$langue]['versa']}</dt>
				<dd>${apacheVersion} &nbsp;</dd>
				<dt>{$langues[$langue]['versp']}</dt>
				<dd>${phpVersion} &nbsp;</dd>
				<dt>{$langues[$langue]['versm']}</dt>
				<dd>${mysqlVersion} &nbsp;</dd>
				<dt>{$langues[$langue]['phpExt']}</dt>
				<dd>
					<ul class="the-icons clearfix">
					${phpExtContents}
					</ul>
				</dd>
				<dt>User agent</dt>
				<dd>{$_SERVER['HTTP_USER_AGENT']}</dd>
			</dl>
		</div>
	</div>
	<footer class="footer">
      <div class="container">
        <ul class="footer-links">
          <li class="pull-right">Wamp Index <a href="http://www.pixel-cookers.com">Pixel Cookers</a> Theme</li>
          <li class="pull-right"><iframe src="http://ghbtns.com/github-btn.html?user=pixel-cookers&repo=WampIndexThemePixelCookers&type=watch&count=true"
  allowtransparency="true" frameborder="0" scrolling="0" width="110px" height="20px"></iframe></li>
          <li><a href="http://{$_SERVER['REMOTE_ADDR']}">{$_SERVER['REMOTE_ADDR']}</a></li>
        </ul>
      </div>
    </footer>
	<script src="_pwi/js/jquery.min.js"></script>
	<script src="_pwi/js/bootstrap.min.js"></script>
</body>
</html>
EOPAGE;

echo $pageContents;
?>

<!DOCTYPE html>
<html>

<head>
	<title>Sicroc &raquo; {$page.title|default:"Untitled page"}</title>

	<link rel = "stylesheet" href = "resources/stylesheets/main.css" type = "text/css" />
	<link rel = "shortcut icon" href = "resources/images/sicroc-favicon.png" type = "image/png" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="theme-color" content="#673000">

	<script type = "text/javascript" src = "resources/js/main.js"></script>
</head>

<body>

<header>
	<div id = "permaWidget">
		<button id = "toggleSidebar" onclick = "toggleSidebar()">&raquo;</button>
		<h1><a href = "?">Sicroc</a> &raquo; {$page.title|default:"Untitled page"}</h1>
	</div>

	<nav>
	{if $navigation|@count eq 0}
		<ul class = "navigation">&nbsp; </ul>
	{else}
		<ul class = "navigation">
		{include file = "links.tpl" links = $navigation}
		</ul>
	{/if}

	<ul class = "subnav navigation" id = "sectionActions">
	{include file = "links.tpl" links = $actionNavigation}
	</ul>
	</nav>
</header>


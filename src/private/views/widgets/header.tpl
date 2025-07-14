<!DOCTYPE html>
<html>

<head>
	<title>Sicroc &raquo; {$page.title|default:"Untitled page"}</title>

	<link rel = "stylesheet" href = "assets/index.css" type = "text/css" />
	<link rel = "shortcut icon" href = "resources/images/sicroc-favicon.png" type = "image/png" />

    <meta charset = "UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="theme-color" content="#673000">

	<script src="https://code.iconify.design/iconify-icon/3.0.0/iconify-icon.min.js"></script>

	<script type = "text/javascript" src = "resources/js/main.js"></script>
</head>

<body>

<header id = "sidebar">
	<div class = "logo-and-title">
		<img src = "resources/images/sicroc-favicon.png" alt = "Sicroc logo" class = "logo" />
		<h1><a href = "?">Sicroc</a> &raquo; {$page.title|default:"Untitled page"}</h1>
	</div>
</header>

<div id = "layout">

<aside class = "shown stuck" id = "sidebar">
	{if empty($navigation)}
		<ul class = "navigation">&nbsp; </ul>
	{else}
		<ul class = "navigation">
		{include file = "links.tpl" links = $navigation}
		</ul>
	{/if}

	<div class = "fg1"></div>

	<ul class = "subnav navigation" id = "sectionActions">
	{include file = "links.tpl" links = $actionNavigation}
	</ul>
</aside>


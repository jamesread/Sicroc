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
	<h1><a href = "?">Sicroc</a></h1>

	{if $navigation|@count eq 0}
		<ul class = "navigation">&nbsp; </ul>
	{else}
		<ul class = "navigation">
		{foreach from = $navigation item = "link"}
			<li><a href = "{$link.url}"	class = "{if $page.title == $link.title}activeSection{/if}">{$link.title}</a></li>
		{/foreach}
		</ul>
	{/if}

	<div class = "subnav">
	{if count($actionNavigation) <= 2}
	{$link = $actionNavigation->getAll()}
	{foreach from = $actionNavigation item = link}
	<a href = "{$link.url}" class = "{if $page.title == $link.title}activeSection{/if}">{$link.title}</a>
	{/foreach}

	{else}
	<ul id = "sectionActions">
		<li><span title = "Actions">&#9776;</span>
			<ul>
			{foreach from = $actionNavigation item = "link"}
			{if $link.separator}
			<li><hr /></li>
			{else}
			<li><a href = "{$link.url}">{$link.title}</a></li>
			{/if}
			{/foreach}
			</ul>
		</li>
	</ul>
	{/if}
	</div>
</header>


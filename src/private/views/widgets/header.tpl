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

<header>
	<div class = "logo-and-title" id = "sidebar-button">
		<a href = "/" class = "fg1 flex-row">
			<img src = "resources/images/sicroc-favicon.png" alt = "Sicroc logo" class = "logo" />
			<h1>Sicroc</h1>
		</a>
		<button class = "sidebar-toggle" title = "Toggle sidebar" id = "toggle-icon" onclick = "toggleSidebar()">
			<iconify-icon icon = "mdi:menu" width = "24" height = "24"></iconify-icon>
		</button>
	</div>

	<div class = "fg1"><h1>{$page.title|default:"Untitled page"}</h1></div>

	<nav class = "">
		<ul role = "menubar" id = "sectionActions">
			<li>
				<span>{$username}</span>
				<div class = "user-dropdown">
					<ul>
					{include file = "links.tpl" links = $actionNavigation}
					</ul>
				</div>
			</li>
		</ul>
	</nav>
</header>

<div id = "layout">

<aside class = "" id = "sidebar">
	<div class = "flex-row" style = "padding-top: .5em;">
		<div class = "fg1"></div>

		<button onclick = "toggleSidebarStuck()" class = "sidebar-toggle" style = "text-align: right;" title = "Toggle sidebar" id = "stick-icon">
			<iconify-icon icon = "mdi:pin-outline" width = "24" height = "24"></iconify-icon>
		</button>
	</div>

	{if empty($navigation)}
		<ul class = "navigation">&nbsp; </ul>
	{else}
		<ul class = "navigation">
		{include file = "links.tpl" links = $navigation}
		</ul>
	{/if}
</aside>

<script>
main();
</script>

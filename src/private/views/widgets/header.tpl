<html>

<head>
	<title>Sicroc &raquo; {$page.title|default:"Untitled page"}</title>

	<link rel = "stylesheet" href = "resources/stylesheets/main.css" type = "text/css" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<header>
	<h1><a href = "?">Sicroc</a></h1>

	{if $navigation|@count eq 0}
		<p>No subsections. </p>
	{else}
		<ul class = "navigation">
		{foreach from = $navigation item = "link"}
			<li>
			<a href = "{$link.url}"	class = "{if $section.title == $link.title}activeSection{/if}">{$link.title}</a></li>
		{/foreach}
		</ul>
	{/if}

	<div class = "subnav">
	<ul id = "sectionActions">
		<li><span title = "Actions">&#9776;</span>
			<ul>
				<li><strong>Admin</strong></li>
				<li><a href = "?pageIdent=USER_PREFERENCES">User Preferences</a></li>
				<li><a href = "?pageIdent=ADMIN">Control Panel</a></li>
				<li><a href = "setup.php">Rerun Setup</a></li>
				<li><strong>Section</strong></li>
				<li><a href = "?pageIdent=SECTION_LIST">Section list</a></li>
				<li><a href = "?pageIdent=SECTION_CREATE">Create section</a></li>
				<li><strong>Tables</strong></li>
				<li><a href = "?pageIdent=TABLE_CONFIGURATION_LIST">TC List</a></li>
				<li><a href = "?pageIdent=TABLE_CONFIGURATION_CREATE">Create Table Configuration</a></li>
				<li><strong>Page</strong></li>
				<li><a href = "?pageIdent=PAGE_LIST">Page list</a>
				<li><a href = "?pageIdent=PAGE_CREATE">Create page</a>
				<li><strong>Widgets</strong></li>
				<li><a href = "?pageIdent=WIDGET_LIST">Widget Instance List</a></li>
				<li><a href = "?pageIdent=WIDGET_CREATE">Create Widget Instance</a></li>
				<li><a href = "?pageIdent=WIDGET_REGISTER">Register widget class</a></li>
				<li><strong>Current view</strong></li>
				<li><a href = "?pageIdent=SECTION_UPDATE&sectionToEdit={$section.id}">Update section</a></li>
				<li><a href = "?pageIdent=PAGE_UPDATE&pageToEdit={$page.id}">Update page</a></li>
				<li><strong>Account</strong></li>
			{if $isLoggedIn}
				<li><a href = "?pageIdent=LOGOUT">Logout</a></li>
			{else}
				<li><a href = "?pageIdent=LOGIN">Login</a></li>
			{/if}

			</ul>
		</li>
	</ul>
	</div>
</header>


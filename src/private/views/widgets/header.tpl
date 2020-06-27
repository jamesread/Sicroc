<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html>

<head>
	<title>Sicroc &raquo; {$page.title|default:"Untitled page"}</title>

	<link rel = "stylesheet" href = "resources/stylesheets/main.css" type = "text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>

<div id = "header">
	<h1><a href = "?">Sicroc</a> &raquo; {$page.title|default:"Untitled page"}</h1>

	{if $navigation|@count eq 0}
		<p>No subsections. </p>
	{else}
		<ul class = "navigation">
		{foreach from = $navigation item = "link"}
			<li><a href = "{$link.url}">{$link.caption}</a></li>
		{/foreach}
		</ul>
	{/if}

	<div class = "subnav">
		<p>Hi {$user.username}</p>

		<ul id = "sectionActions">
			<li>Actions
				<ul>
					<li><strong>Admin</strong></li>
					<li><a href = "?pageIdent=ADMIN">Control Panel</a></li>
					<li><strong>Section</strong></li>
					<li><a href = "?pageIdent=SECTION_LIST">Section list</a></li>
					<li><a href = "?pageIdent=SECTION_CREATE">Create section</a></li>
					<li><strong>Page</strong></li>
					<li><a href = "?pageIdent=PAGE_LIST">Page list</a>
					<li><a href = "?pageIdent=PAGE_CREATE">Create page</a>
					<li><strong>Widgets</strong></li>
					<li><a href = "?pageIdent=WIDGET_CREATE">Create Widget Instance</a></li>
					<li><a href = "?pageIdent=WIDGET_REGISTER">Register widget class</a></li>
					<li><strong>Current view</strong></li>
					<li><a href = "?pageIdent=SECTION_UPDATE&sectionToEdit={$section.id}">Update section</a></li>
					<li><a href = "?pageIdent=PAGE_UPDATE&pageToEdit={$page.id}">Update page</a></li>
					<li><strong>Account</strong></li>
				{if $isLoggedIn}
					<li><a href = "?pageIdent=LOGOUT">Logout</a></li>
				{/if}

				</ul>
			</li>
		</ul>

		<div class = "clearer"></div>
	</div>
</div>

<div class = "page">

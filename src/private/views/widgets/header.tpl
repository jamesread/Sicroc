<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  
<html>

<head>
	<title>Sicroc :: {$page.title|default:"Untitled page"}</title>

	<link rel = "stylesheet" href = "resources/stylesheets/main.css" type = "text/css" />
</head>

<body>

<div id = "header">
	<h1><a href = "?">Sicroc</a> :: {$page.title|default:"Untitled page"}</h1>

	{if $navigation|@count eq 0}
		<p>No subsections. </p>
	{else}
		<ul class = "navigation">
		{foreach from = "$navigation" item = "link"}
			<li><a href = "{$link.url}">{$link.caption}</a></li>
		{/foreach}
		</ul>
	{/if}

	<div class = "subnav">
		<p>Hi {$user.username}</p>

		<ul id = "sectionActions">
			<li>Actions
				<ul>
				{if $isLoggedIn}
					<li><a href = "?pageIdent=LOGOUT">Logout</a></li>
				{/if}
					<li><strong>Section</strong></li>
					<li><a href = "?pageIdent=SECTION_UPDATE&sectionToEdit={$section.id}">Update section</a></li>
					<li><strong>Page</strong></li>
					<li><a href = "?page=4">Create page</a>
					<li><a href = "?page=6&pageToEdit={$page.id}">Update page</a></li>
					<li><strong>Widgets</strong></li>
					<li><a href = "?pageIdent=WIDGET_CREATE">Create Widget Instance</a></li>
					<li><a href = "?pageIdent=WIDGET_REGISTER">Register widget class</a></li>
				</ul>
			</li>
		</ul>

		<div class = "clearer"></div>
	</div>
</div>

<div class = "page">

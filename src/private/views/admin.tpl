<h2>Admin</h2>
This is the administration fo shizzle.

{foreach item = "module" from = "modules"}
<h2>{$module.name}</h2>
<div class = "contentBox">
	<label>Module Description:</label> {$module.description}<br />
	<label>Version:</label> {$module.version}<br />

	<hr />

	<ul>
		{foreach item = "link" from = "moduleLinkList"}
		<li><a href = "{$link.url}">{$link.caption}</a></li>
		{/foreach}
	</ul>
</div>
{/foreach}


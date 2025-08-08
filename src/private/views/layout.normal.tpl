{include file = "widgets/header.tpl"}

<div id = "content">
<main class = "{$additionalClasses}">

{if empty($widgets)}
	<p>This page is empty... <a href = "dispatcher.php?pageIdent=PAGE_UPDATE&amp;pageToEdit={$page.id}">Update</a>?</p>
{else}
	{foreach from = $widgets item = widget}  

	{if !$widget.inst->shouldRender()}
		{continue}
	{/if}
	<section class = "widget with-header-and-content">
		<div class = "section-header">
			<div class = "fg1">
				<h2 title = "ID: {$widget.id}. VC: {$widget.viewableController}">{$widget.title|default:"Untitled widget"}</h2>
			</div>

			<div role = "toolbar">
			{if $widget.inst->displayEdit}
				<a href = "?controller=Page&amp;pageIdent=WIDGET_INSTANCE_UPDATE&amp;widgetToUpdate={$widget.id}" class = "button" title = "Edit this widget">
					<span class = "hide-sm">Edit Widget</span>
					<iconify-icon icon = "hugeicons:edit-03"></iconify-icon>
				</a>
			{/if}
			</div>
		</div>

		{if isset($widget.inst->navigation) && $widget.inst->navigation->hasLinks()}
			<div class = "toolbar padded">
			{foreach from = $widget.inst->navigation item = link}
				{if $link.url == null}
				&nbsp;&nbsp;
				{else}
					{if $link.containerClass == "noLink"}
					<strong>{$link.title}</strong>
					{else}
					<a class = "button" href = "{$link.url}">{$link.title}</a>
					{/if}
				{/if}
			{/foreach}
			</div>
		{/if}

		<div class = "section-content">
		{$widget.content}
		</div>
	</section>
	{/foreach}
{/if}

<div style = "float: right">
	{if $editMode}
	<abbr title = "Page ID: {$page.id}">Page</abbr>: 

	{if $page.isSystem}
		<span title = "System page">&#9881;</span>
	{/if}

	{/if}

	{if $editMode}
	<a href = "?pageIdent=PAGE_UPDATE&amp;pageToEdit={$page.id}" class = "button">
		Edit Page
		<iconify-icon icon = "hugeicons:edit-03"></iconify-icon>
	</a>
	{/if}
</div>

</main>

{include file = "widgets/footer.tpl"}
</div>

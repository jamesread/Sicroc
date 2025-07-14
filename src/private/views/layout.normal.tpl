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
	<section class = "widget">
		<div style = "float: right;">
			{if $widget.inst->displayEdit}
				<a href = "?controller=Page&amp;pageIdent=WIDGET_INSTANCE_UPDATE&amp;widgetToUpdate={$widget.id}">&#x270E;</a>
			{/if}
		</div>

		{if count($widgets) != 1}
		<h3 title = "ID: {$widget.id}. VC: {$widget.viewableController}">{$widget.title|default:"Untitled widget"}</h3>
		{/if}

		{if isset($widget.inst->navigation) && $widget.inst->navigation->hasLinks()}
			<div class = "toolbar">
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

		{$widget.content}
	</section>
	{/foreach}
{/if}

{if count($widgets) != 1}
<div style = "float: right">
	{if $editMode}
	<abbr title = "Page ID: {$page.id}">Page</abbr>: 

	{if $page.isSystem}
		<span title = "System page">&#9881;</span>
	{/if}

	{/if}

	{if $editMode}
	<a href = "?pageIdent=PAGE_UPDATE&amp;pageToEdit={$page.id}">&#x270E;</a>
	{/if}
</div>
{/if}

</main>

{include file = "widgets/footer.tpl"}
</div>

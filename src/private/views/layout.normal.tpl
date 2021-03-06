{include file = "widgets/header.tpl"}

<h2><abbr title = "{$page.id}">Page</abbr>: {$page.title|default:"Untitled page"} <a href = "?pageIdent=PAGE_UPDATE&amp;pageToEdit={$page.id}">&#x270E;</a>
	{if $page.isSystem}
		&#9888;
	{/if}
</h2>

{if $widgets|@count eq 0}
	<p>This page is empty... <a href = "dispatcher.php?pageIdent=PAGE_UPDATE&amp;pageToEdit={$page.id}">Update</a>?</p>
{else}
	{foreach from = $widgets item = widget}  
	<div class = "widget">
		<div style = "float: right;">
			{if $widget.inst->displayEdit}
				<a href = "?controller=Page&amp;pageIdent=WIDGET_INSTANCE_UPDATE&amp;widgetToUpdate={$widget.id}">&#x270E;</a>
			{/if}
		</div>

		<h3 title = "ID: {$widget.id}. VC: {$widget.viewableController}">{$widget.title|default:"Untitled widget"}</h3>

		{if isset($widget.inst->navigation) && $widget.inst->navigation->hasLinks()}
			<div class = "toolbar">
			{foreach from = $widget.inst->navigation item = link}
				{if $link.url == null}
				|
				{else}
				<a class = "button" href = "{$link.url}">{$link.title}</a>
				{/if}
			{/foreach}
			</div>
		{/if}

		{$widget.content}
	</div>
	{/foreach}
{/if}


{include file = "widgets/footer.tpl"}

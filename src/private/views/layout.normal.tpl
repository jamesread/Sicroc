{include file = "widgets/header.tpl"}

<h2><abbr title = "{$page.id}">Page</abbr>: {$page.title|default:"Untitled page"} <a href = "?pageIdent=PAGE_UPDATE&amp;pageToEdit={$page.id}">&#x270E;</a></h2>

{if $widgets|@count eq 0}
	<p>This page is empty... <a href = "dispatcher.php?pageIdent=PAGE_UPDATE&amp;pageToEdit={$page.id}">Update</a>?</p>
{else}
	{foreach from = $widgets item = widget}  
	<div class = "widget">
		{if sizeof($widgets) > 1}
		<h3><abbr title = "ID: {$widget.id}. VC: {$widget.viewableController}">{$widget.title|default:"Untitled widget"}</abbr> <a href = "?controller=Page&amp;pageIdent=WIDGET_INSTANCE_UPDATE&amp;widgetToUpdate={$widget.id}">&#x270E;</a></h3>
		{else}
		<a href = "?controller=Page&amp;pageIdent=WIDGET_INSTANCE_UPDATE&amp;widgetToUpdate={$widget.id}">&#x270E;</a>
		{/if}
		{if isset($widget.inst->navigation) && $widget.inst->navigation->hasLinks()}
			<div class = "toolbar">
			{foreach from = $widget.inst->navigation item = link}
				<a class = "button" href = "{$link.url}">{$link.title}</a>
			{/foreach}
			</div>
		{/if}

		{$widget.content}
	</div>
	{/foreach}
{/if}


{include file = "widgets/footer.tpl"}

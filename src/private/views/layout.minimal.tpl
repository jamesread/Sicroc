{include file = "widgets/header.minimal.tpl"}

<div class = "page">
	<h2 title = "Page ID: {$page.id}">{$page.title}</h2>

{foreach from = $widgets item = widget}  
	<div class = "widget">
	{$widget.content}
	</div>
{/foreach}
</div>

{include file = "widgets/footer.tpl"}

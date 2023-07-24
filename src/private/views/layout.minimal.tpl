{include file = "widgets/header.minimal.tpl"}

<main>
	<h2 title = "Page ID: {$page.id}">{$page.title}</h2>

{foreach from = $widgets item = widget}  
	<div class = "widget">
	{$widget.content}
	</div>
{/foreach}
</main>

{include file = "widgets/footer.tpl"}

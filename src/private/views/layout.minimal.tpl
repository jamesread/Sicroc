{include file = "widgets/header.minimal.tpl"}
{$page.title} - {$page.id}
{$widgets|@count}
{foreach from = "$widgets" item = "widget}  
WID
	{$widget.content}
{/foreach}
</body>

</html>

{include file = "widgets/footer.minimal.tpl"}

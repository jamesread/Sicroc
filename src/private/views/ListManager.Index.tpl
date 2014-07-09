{include file = "widgets/header.tpl"}

<ul>
{foreach from = "$lists" item = "list"}
	<li>{$list.modifyLink}</li>
{/foreach}
</ul>

{include file = "widgets/footer.tpl"}

{include file = "widgets/header.tpl"}


{foreach from = "$parent" item = "child"}
	<div class = "vend-parent"> 
		<h1>parent</h1>

		<div class = "vend-child">
			<h2>child</h2>
		</div>
	</div>
{/foreach}

{include file = "widgets/footer.tpl"}

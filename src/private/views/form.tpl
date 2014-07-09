<div class = "box">
	<!-- FORM:{$form->getName()} (rendered by template engine) !-->
	<form enctype = "{$form->getEnctype()}" id = "{$form->getName()}" action = "{$form->getAction()}" method = "post">
		{include file = "formElements.tpl" elements="$elements"}

		{foreach from = "$scripts" item = "script"}
			<script type = "text/javascript">
			</script>
		{/foreach}

	</form>

</div>

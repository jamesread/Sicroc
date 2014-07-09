
<div>
{foreach from = "$elements" item = "element"}
	{if is_array($element)}
		{include file = "formElements.tpl" elements="$element"}
	{else}
		{if $element->getType() eq 'hidden'}
			<input type = "hidden" name = "{$element->getName()}" value = "{$element->getValue()}" />
		{elseif $element->getType() eq 'html'}
			{$element->getValue()}
		{elseif $element->getType() eq 'submit'}
			<button value = "{$form->getName()}" name = "{$element->getName()}" type = "submit">{$element->getCaption()}</button>
		{elseif $element->getType() eq 'ElementHidden'}
			{$element->render()}
		{else}
			<fieldset>				
				{$element->render()}

				{if $element->description ne ''}
				<p class = "description">{$element->description}</p>
				{/if}

				{if $element->getValidationError() ne ''}
				<p class = "formValidationError">{$element->getValidationError()}</p>
				{/if}
			</fieldset>
		{/if}
	{/if}
{/foreach}

</div>


{foreach from = $elements item = element}
	{if is_array($element)}
		{include file = "formElements.tpl" elements=$element}
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
			<label class = "{($element->isRequired()) ? 'required' : 'optional'}>" for = "{$element->getName()}">{$element->getCaption()}</label>
			
			{$element->render()}

			<div>
				<p class = "description">{$element->description}</p>

				{if !empty($element->getSuggestedValues())}
				<div>
					{foreach from = $element->getSuggestedValues() key = sv item = caption}
						<span class = "dummyLink" onclick = "document.getElementById('{$element->getName()}').value = '{$sv} '">{$caption}</span>
					{/foreach}
				</div>
				{/if}
			</div>

			{if $element->getValidationError() ne ''}
			<p class = "formValidationError">{$element->getValidationError()}</p>
			{/if}
		{/if}
	{/if}
{/foreach}

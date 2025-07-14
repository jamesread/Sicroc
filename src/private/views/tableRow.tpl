<div class = "box">
<table class = "transparent">
	{foreach from = $row item = cellValue key = cellName}
	{if $cellName == "meta"}{continue}{/if}

	<tr>
		<td class = "keyCol">{$cellName}:</td>
		<td>
		{if !empty($cellValue)}
			{$cellValue|htmlentities}
		{/if}
		</td>
	</tr>
	{/foreach}
</table>
</div>

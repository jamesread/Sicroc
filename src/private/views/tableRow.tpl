<div class = "box">
<table class = "transparent">
	{foreach from = $row item = cellValue key = cellName} 
	<tr>
		<td class = "keyCol">{$cellName}:</td>
		<td>{$cellValue}</td>
	</tr>
	{/foreach}
</table>
</div>

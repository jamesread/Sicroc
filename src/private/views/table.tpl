<table>
	<thead>
		<tr>
		{foreach from = "$headers" item = "header"} 
			<th>
				{$header.name} 
				<span class = "subtle">{if isset($header.native_type)}{$header.native_type}{else}???{/if}</span>
			</th>
		{/foreach}
		</tr>
	</thead>

	<tbody>
	{foreach from = $rows item = row}
	<tr>
		{foreach from = $row item = cell key = key}
		<td>
			{if $table.primaryKey == $key}
				<a href = "?pageIdent=TABLE_ROW&amp;table={$table.name}&amp;primaryKey={$cell}">{$cell}</a> | 
				<a href = "?pageIdent=TABLE_ROW_EDIT&amptable={$table.name}&tamp;primaryKey={$cell}&amp;redirectTo={$page.id}">edit</a>
			{else}
				{$cell}
			{/if}
		</td>
		{/foreach}
	</tr>
	{/foreach}
	</tbody>
</table>

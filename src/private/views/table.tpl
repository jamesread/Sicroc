<table>
	<thead>
		<tr>
		{foreach from = $headers item = "header" key = key} 

		{if $key == $primaryKey && !$tc->showId}{continue}{/if}

			<th>
				{$header.name} 
				{if $showTypes}
				<span class = "subtle">{if isset($header.native_type)}{$header.native_type}{else}???{/if}</span>
				{/if}
			</th>
		{/foreach}
		</tr>
	</thead>

	<tbody>

	{foreach from = $rows item = row}
	<tr>
		{foreach from = $row item = cell key = key}

		{if $key == $primaryKey && !$tc->showId}{continue}{/if}

		<td>
			{if $primaryKey == $key}
				<a href = "?pageIdent=TABLE_ROW&amp;tc={$tc->id}&amp;primaryKey={$cell}">{$cell}</a> |
				<a href = "?pageIdent=TABLE_ROW_EDIT&amp;tc={$tc->id}&amp;primaryKey={$cell}&amp;redirectTo={}">edit</a>
			{else}
				{$cell}
			{/if}
		</td>
		{/foreach}
	</tr>
	{/foreach}
	</tbody>
</table>

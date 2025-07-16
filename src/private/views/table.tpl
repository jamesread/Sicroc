<table class = "data hover">
	<thead>
		<tr>
		{foreach from = $headers item = "header" key = key name = headers} 

		{if $key == $primaryKey && !$tc->showId}{continue}{/if}

			<th>
				<a href = "#" onclick = "javascript:sortColumn(this.closest('table'), this.closest('th'), '{$smarty.foreach.headers.index}')">{$header.name}</a>
				{if $showTypes}
				<br />
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
		{if stripos($key, '_fk_description') !== false}{continue}{/if}
		{if $key == 'meta'}{continue}{/if}

		<td style = "{if $key == $row['meta']['cell_style_field']}{$row['meta']['cell_style']}{/if}">
			{if $primaryKey == $key}
				<a href = "?pageIdent=TABLE_ROW&amp;tc={$tc->id}&amp;primaryKey={$cell}">{$cell}</a> |
				<a href = "?pageIdent=TABLE_ROW_EDIT&amp;tc={$tc->id}&amp;primaryKey={$cell}&amp;redirectTo={}">edit</a>
			{else}
				{if $headers[$key]['native_type'] == 'TINY'}
					{if $cell == 1}
					&#10004;
					{else}
					&#10005;
					{/if}
				{else if $cell === null}
				<span class = "subtle">NULL</span>
				{else}
					{$desc="`$key`_fk_description"}
					{if isset($row[$desc])}
						{if \libAllure\Session::getUser()->getData('fkStyle') == 'DESC_ONLY'}
						{$row[$desc]}
						{else}
						{$cell} ({$row[$desc]})
						{/if}
					{else}
					{$cell}
					{/if}
				{/if}

				{/if}
		</td>
		{/foreach}
	</tr>
	{/foreach}
	</tbody>
</table>

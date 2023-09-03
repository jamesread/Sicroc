<table class = "calendar">
<tr>
	<th>M<span class = "hideThin">on</span></th>
	<th>T<span class = "hideThin">ue</span></th>
	<th>W<span class = "hideThin">ed</span></th>
	<th>T<span class = "hideThin">hu</span></th>
	<th>F<span class = "hideThin">ri</span></th>
	<th>S<span class = "hideThin">at</span></th>
	<th>S<span class = "hideThin">un</span></th>
</tr>
{foreach from = $weeks item = week}
	<tr>
	{foreach from = $week.days item = day}
	<td class = "{if $day.weekend}weekend{/if} {if $day.anotherMonth}anotherMonth{/if} {if $day.today}today{/if}">
		<p class = "dayTitle">
			<a href = "?pageIdent=TABLE_INSERT&tc={$tc->id}&datetime={$day.datetime} 00:00">{$day.day} <span class = "hideThin">{$day.month}</span></a>
		</p>

		<ul>
		{foreach from = $day.events item = event}
			<li><a href = "{$event.url}">{$event.title}</a></li>
		{/foreach}
		</ul>
	</td>
	{/foreach}
	</tr>
{/foreach}
</table>

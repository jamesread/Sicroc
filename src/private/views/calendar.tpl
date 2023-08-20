<h2>
	<a href = "?page={$pid}&start={$datePrev}">&laquo;</a> 
	{$dateStart} til {$dateFinish} 
	<a href = "?page={$pid}&start={$dateNext}">&raquo;</a>
</h2>

<table class = "calendar">
<tr>
	<th>Mon</th>
	<th>Tue</th>
	<th>Wed</th>
	<th>Thu</th>
	<th>Fri</th>
	<th>Sat</th>
	<th>Sun</th>
</tr>
{foreach from = $weeks item = week}
	<tr>
	{foreach from = $week.days item = day}
	<td class = "{if $day.weekend}weekend{/if} {if $day.today}today{/if}">
		<p class = "dayTitle">
			<a href = "?pageIdent=TABLE_INSERT&tc={$tc}&datetime={$day.datetime} 00:00">{$day.title}</a>
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

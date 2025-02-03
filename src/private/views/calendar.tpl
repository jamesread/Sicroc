<div class = "calendar">
	<div class = "header">M<span class = "hideThin">on</span></div>
	<div class = "header">T<span class = "hideThin">ue</span></div>
	<div class = "header">W<span class = "hideThin">ed</span></div>
	<div class = "header">T<span class = "hideThin">hu</span></div>
	<div class = "header">F<span class = "hideThin">ri</span></div>
	<div class = "header">S<span class = "hideThin">at</span></div>
	<div class = "header">S<span class = "hideThin">un</span></div>
{foreach from = $weeks item = week}
	{foreach from = $week.days item = day}
	<div class = "{if $day.weekend}weekend{/if} {if $day.anotherMonth}anotherMonth{/if} {if $day.today}today{/if}">
		<p class = "dayTitle">
			<a href = "?pageIdent=TABLE_INSERT&tc={$tc->id}&datetime={$day.datetime} 00:00">{$day.day} <span class = "hideThin">{$day.month}</span></a>
		</p>

		<ul>
		{foreach from = $day.events item = event}
			<li><a href = "{$event.url}">{$event.datetime}{$event.title|htmlentities}</a></li>
		{/foreach}
		</ul>
	</div>
	{/foreach}
{/foreach}
</div>

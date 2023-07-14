<div class = "wikiPage">
{if empty($wikiPage)}
	Wiki page name is not set.
{else}
	{if empty($wikiPage.content)}
		Wiki page is was not found: {$wikiPage.title}
	{else}
		{$wikiPage.content}
	{/if}
{/if}
</div>

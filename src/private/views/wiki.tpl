<div class = "wikiPage">
{if empty($wikiPage)}
	Wiki page name is not set.
{else}
	{if empty($wikiPage.content)}
		Wiki page has no content: {$wikiPage.title}
	{else}
		{$wikiPage.content}
	{/if}
{/if}
</div>

{foreach from = $links item = "link"}
			{if isset($link['separator']) && $link['separator']}
				<hr />
				{continue}
			{/if}

			<li>
				<a href = "{$link.url}"	class = "{if $page.title == $link.title}activeSection{/if}">
					<div class = "icon-holder">
						{if isset($link.iconUrl)}
							<iconify-icon icon="{$link.iconUrl}" width="24" height="24"></iconify-icon>
						{else}
							<iconify-icon icon="mingcute:question-fill" width="24" height="24"></iconify-icon>
						{/if}
					</div>
					{$link.title}
				</a>
			</li>
{/foreach}


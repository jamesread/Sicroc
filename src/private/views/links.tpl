{foreach from = $links item = "link"}
			{if isset($link['separator']) && $link['separator']}
				<hr />
				{continue}
			{/if}

			<li>
				
				<a href = "{$link.url}"	class = "{if $page.id == 0}activeSection{/if}">
					<span class = "inline-icon">
						{if isset($link.iconUrl)}
							<iconify-icon icon="{$link.iconUrl}" width="24" height="24"></iconify-icon>
						{else}
							<iconify-icon icon="mingcute:question-fill" width="24" height="24"></iconify-icon>
						{/if}
					</span>
					{$link.title}

				</a>
			</li>

			{if !empty($link.children)}
			<div class = "level2-nav">
				{include file = "links.tpl" links = $link.children}
			</div>
			{/if}

{/foreach}


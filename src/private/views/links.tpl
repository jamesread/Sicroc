		{foreach from = $links item = "link"}
			{if isset($link['separator']) && $link['separator']}
				<hr />
				{continue}
			{/if}
			<li><a href = "{$link.url}"	class = "{if $page.title == $link.title}activeSection{/if}">{$link.title}</a>
			</li>

			{if count($link['children']) > 0}
				<ul class = "navigation">
				{include file = "links.tpl" links = $link['children']}
				</ul>
			{/if}
		{/foreach}


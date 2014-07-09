{include file = "widgets/header.tpl"}

Dis is the blog!

{foreach from = "$blogPosts" item = "post"}
	<div class = "box">
		<h2>{$post.title}</h2>
		
		{$post.content}
		
		<br />
	
		<span class = "subtle">Posted by: {$post.author}</span>
	</div>
{/foreach}

{include file = "widgets/footer.tpl"}

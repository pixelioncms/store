<blockquote>
    <cite><?=$post->user->login?> сказал(а) <?=CMS::date($post->date_create,true,true)?>:</cite> 
    <div><p><?= $post->text?></p></div>
</blockquote>
<br/>
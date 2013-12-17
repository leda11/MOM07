<h1>My Theme -example</h1>
<p>This is a helper to aid in theme developing and testing.<p>
<p>Current theme is: <?=$theme_name?></p>

Helpsites<br/>

</ul>
<?php foreach($methods as $val): ?>
<li><a href='<?=create_url($val)?>'><?=$val?></a>
<?php endforeach; ?>
</ul>



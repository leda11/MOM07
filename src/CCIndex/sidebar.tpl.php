<div class='box'>
    <h4>Controllers and methods</h4>
    <p>The following controllers exists. You enable and disable controllers in<br/>
    <code>site/config.php</code>.</p>
    
    <p>All Handy modules.</p>
    <ul>
<?php foreach($controllers as $key => $val): ?>
<li><a href='<?=create_url($key)?>'><?=$key?></a></li>

<?php if(!empty($val)): ?>
<ul>
<?php foreach($val as $method): ?>
<li><a href='<?=create_url($key, $method)?>'><?=$method?></a></li>
<?php endforeach; ?>                
</ul>
<?php endif; ?>
<?php endforeach; ?>                
</ul>
</div>



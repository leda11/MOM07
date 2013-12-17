<h1>Blog Content.</h1>
    <p>View of all blog posts. "type" is post</p>
    <a href='<?=create_url("content")?>'>view all content</a>.
    
    <h2>All blogs</h2>
    <?php if($contents != null):?>
      
      <?php foreach($contents as $val):?>
      <p>
      <h3><?=esc($val['title'])?></h3>
      <p><?=filter_data($val['data'], $val['filter'])?></p>
      <div class='smaller-text silent'>Created <?=$val['created']?> by <?=$val['owner']?>: 
      
      <a href='<?=create_url("content/edit/{$val['id']}")?>'> Edit</a></div>
      <hr>
      </p>
      
      <?php endforeach; ?>
      
    <?php else:?>
      <p>No posts exists.</p>
    <?php endif;?>

    
     
      



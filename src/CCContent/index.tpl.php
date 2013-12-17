    <h1>Content Controller Index</h1>
    <p>One controller to manage the actions for content, mainly list, create, edit, delete, view.</p>

    <h2>All content</h2>
    <?php if($content != null):?>
      <ul>
      <?php foreach($content as $val):?>
      <li><?=$val['id']?>: <?=$val['title']?> by <?=$val['owner']?> <a href='<?=create_url("content/edit/{$val['id']}")?>'>edit</a>
     <a href='<?=create_url("page/view/{$val['id']}")?>'>view</a>
     
      <?php 
      endforeach; ?>
     </ul>
    <?php else:?>
      <p>No content exists.</p>
    <?php endif;?>

    <h2>Actions</h2>
    <ul>
      <li><a href='<?=create_url('content/init')?>'>Init database, create tables and sample content</a>
      <li><a href='<?=create_url('content/create')?>'>Create new content</a>
      <li><a href='<?=create_url('blog')?>'>view blog</a>

    </ul>


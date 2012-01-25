<?php
$head = array('title' => html_escape('Image Resize'));
head($head);
?>
<h1><?php echo $head['title']; ?></h1>
<div id="primary">
<?php echo flash(); ?>

<form method="post">
<table>
    <thead>
    <tr>
        <th>Image type</th>
        <th>Size constraint (pixels)</th>
        <th>Resize?</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Fullsize image</td>
        <td><?php echo $this->formText('fullsize_constraint', get_option('fullsize_constraint'), array('size' => '6')); ?></td>
        <td><?php echo $this->formCheckbox('resize_fullsize_constraint'); ?></td>
    </tr>
    <tr>
        <td>Thumbnail</td>
        <td><?php echo $this->formText('thumbnail_constraint', get_option('thumbnail_constraint'), array('size' => '6')); ?></td>
        <td><?php echo $this->formCheckbox('resize_thumbnail_constraint'); ?></td>
    </tr>
    <tr>
        <td>Square thumbnail</td>
        <td><?php echo $this->formText('square_thumbnail_constraint', get_option('square_thumbnail_constraint'), array('size' => '6')); ?></td>
        <td><?php echo $this->formCheckbox('resize_square_thumbnail_constraint'); ?></td>
    </tr>
    </tbody>
</table>
<?php echo $this->formSubmit('image_resize_submit', 'Resize Selected Images'); ?>
</form>

<h2>Image Resize Processes</h2>
<table>
    <thead>
    <tr>
        <th>Resize action</th>
        <th>Status</th>
        <th>Started</th>
        <th>Stopped</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($processes as $process): ?>
    <?php
    $constraints = unserialize($process->args);
    $resizeAction = '';
    foreach ($constraints as $key => $constraint) {
        if ($constraint) {
            $resizeAction .= "$key: {$constraint}<br />";
        }
    }
    ?>
    <tr>
        <td><?php echo $resizeAction; ?></td>
        <td><?php echo $process->status; ?></td>
        <td><?php echo $process->started; ?></td>
        <td><?php echo $process->stopped; ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</div>
<?php foot(); ?>
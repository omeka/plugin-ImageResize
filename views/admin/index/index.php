<?php
$head = array('title' => html_escape('Image Resize'));
head($head);
?>
<h1><?php echo $head['title']; ?></h1>
<div id="primary">
<?php echo flash(); ?>

<?php if (!$usingFilesystemAdapter): ?>
<p style="color: red;">Your Omeka installation is not using the filesystem 
storage adapter. You will not be able to resize images.</p>
<?php else: ?>
<p>Use the below form to resize image derivatives in your Omeka archive 
(fullsize image, thumbnail, and square thumbnail). We highly recommended that 
you backup the archive directory before resizing images.</p>
<form method="post">
<table>
    <thead>
    <tr>
        <th>Image Type</th>
        <th>Size Constraint (in pixels)</th>
        <th>Select to Resize</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Fullsize Image</td>
        <td><?php echo $this->formText('fullsize_constraint', get_option('fullsize_constraint'), array('size' => '6')); ?></td>
        <td><?php echo $this->formCheckbox('resize_fullsize_constraint'); ?></td>
    </tr>
    <tr>
        <td>Thumbnail</td>
        <td><?php echo $this->formText('thumbnail_constraint', get_option('thumbnail_constraint'), array('size' => '6')); ?></td>
        <td><?php echo $this->formCheckbox('resize_thumbnail_constraint'); ?></td>
    </tr>
    <tr>
        <td>Square Thumbnail</td>
        <td><?php echo $this->formText('square_thumbnail_constraint', get_option('square_thumbnail_constraint'), array('size' => '6')); ?></td>
        <td><?php echo $this->formCheckbox('resize_square_thumbnail_constraint'); ?></td>
    </tr>
    </tbody>
</table>
<?php echo $this->formSubmit('image_resize_submit', 'Resize Selected Images'); ?>
</form>
<?php endif; ?>

<h2>Image Resize Processes</h2>
<?php if (!$processes): ?>
<p>There are no image resize processes.</p>
<?php else: ?>
<table>
    <thead>
    <tr>
        <th>Resize Action</th>
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
<?php endif; ?>

</div>
<?php foot(); ?>
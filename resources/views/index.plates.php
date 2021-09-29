<?php
/**
 * @var Pollen\ViewExtends\PlatesTemplateInterface $this
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $this->asset_head(); ?>
    <script src="http://localhost:9000/dist/app.js" defer></script>
</head>
<body>
    <div id="app" data-username="<?php echo $this->get('name'); ?>"></div>
    <?php echo $this->asset_footer(); ?>
</body>
</html>

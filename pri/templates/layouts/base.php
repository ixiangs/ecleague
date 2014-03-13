<!DOCTYPE html>
<html>
  <head>
  	<meta charset="utf-8">
    <title><?php echo $this->renderBlock('title'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/pub/assets/css/bootstrap.min.css" rel="stylesheet">
    <?php echo $this->renderBlock('css'); ?>
    <?php echo $this->renderBlock('headerjs'); ?>
  </head>
  <body>
  	<?php echo $this->renderBlock('body'); ?>
    <script src="/pub/assets/js/libs.js"></script>
    <script src="/pub/assets/js/core.js"></script>
    <script src="/pub/assets/js/locale.js"></script>
    <script src="/pub/assets/js/formvalidate.js"></script>
    <script>
      var behavior = new Behavior({
        // verbose: true
      }).apply(document.body);
      var delegator = new Delegator({
        getBehavior: function(){ return behavior; }
      }).attach(document.body);
    </script>
    <?php echo $this->renderBlock('footerjs'); ?>
  </body>
</html>
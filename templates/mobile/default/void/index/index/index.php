<?php
$this->beginScript('mobile_index');
?>
<script language="javascript">
    document.body.setStyles({
        'background-image':'url(<?php echo $this->applicationContext->website->getBackgroundImage()?>)',
        'background-size': 'cover',
        'background-repeat': 'no-repeat',
        'background-attachment': 'fixed',
        'background-position':'center'
    });
</script>
<?php
$this->endScript();
echo $this->includeTemplate('layout/base');
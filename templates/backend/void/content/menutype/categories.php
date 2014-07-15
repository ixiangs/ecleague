<?php $this->beginScript('content_menutype'); ?>
    <script language="javascript">
        window.addEvent('domready', function () {
            $('link').set('value', 'void_content/category/list').set('readonly', 'readonly');
        });
    </script>
<?php
$this->endScript();
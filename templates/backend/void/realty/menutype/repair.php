<?php $this->beginScript('realty_menutype'); ?>
    <script language="javascript">
        window.addEvent('domready', function () {
            $('link').set('value', 'void_realty/uptown/repair').set('readonly', 'readonly');
        });
    </script>
<?php
$this->endScript();
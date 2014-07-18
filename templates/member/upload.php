<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="<?php echo CSS_URL; ?>bootstrap.css" rel="stylesheet">
    <link href="<?php echo CSS_URL; ?>font-awesome.css" rel="stylesheet">
    <link href="<?php echo CSS_URL; ?>style.css" rel="stylesheet">
    <link href="<?php echo CSS_URL; ?>backend.css" rel="stylesheet">
    <script src="<?php echo JS_URL; ?>mootools.js"></script>
    <script src="<?php echo JS_URL; ?>locale.js"></script>
    <script src="<?php echo JS_URL; ?>toy/core.js"></script>
    <script src="<?php echo JS_URL; ?>toy/html.js"></script>
    <script src="<?php echo JS_URL; ?>backend.js"></script>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <style>
        body {
            background-color: #ffffff;
            padding: 0;
            margin: 0
        }

        .fileinput-button input {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            opacity: 0;
            -ms-filter: 'alpha(opacity=0)';
            font-size: 200px;
            direction: ltr;
            cursor: pointer;
        }

        input[type=file] {
            display: block;
        }

        .fileupload-buttonbar .btn, .fileupload-buttonbar .toggle {
            margin-bottom: 5px;
        }

        .fileinput-button {
            position: absolute;
            right: 0;
            top: 0;
            overflow: hidden;
        }
    </style>
</head>
<body>
<div class="upload-container" data-max-count="<?php echo $this->maxCount; ?>">
    <?php foreach ($this->files as $file): ?>
        <div class="well">
            <img src="<?php echo $file; ?>" class="img-thumbnail">

            <div class="operation">
                <a target="_blank" href="<?php echo $file; ?>"><?php echo $this->localize->_('look'); ?></a>&nbsp;
                <a href="javascript:void(0)" onclick="javascript:deleteFile(this, '<?php echo $file; ?>')">
                    <?php echo $this->localize->_('delete'); ?></a>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="clearfix"></div>
</div>
<form id="upload_form" method="POST"
      action="<?php echo $this->formAction; ?>" enctype="multipart/form-data">
<span class="btn btn-success fileinput-button">
<span><?php echo $this->localize->_('upload') ?></span>
<input type="file" id="upload_file" name="uploadfile" data-accept="<?php echo $this->accept; ?>"/>
</span>
    <?php foreach ($this->files as $file): ?>
        <input type="hidden" name="existed_files[]" value="<?php echo $file; ?>"/>
    <?php endforeach; ?>
</form>
<script language="javascript">
    $('upload_file').addEvent('change', function () {
        var accept = this.get('data-accept').split(',');
        var value = this.get('value').split('.');
        if (accept.include('.' + value)) {
            $('upload_form').submit();
        }else{
            alert('<?php echo $this->localize->_('err_upload_accept') ?>'.substitute(value));
        }
    });
    function deleteFile(el, url) {
        el.getParent().getParent().destroy();
        $('upload_form').getElements('input[type="hidden"]').each(function (item) {
            if (item.get('value') == url) {
                item.destroy();
            }
        });
        updateParent();
    }
    function updateParent() {
        var values = [];
        $('upload_form').getElements('input[type="hidden"]').each(function (item) {
            values.push(item.get('value'));
        });
        window.parent.updateInput('<?php echo $this->inputId; ?>', values.join(','));
    }
    updateParent();
    <?php
    if ($this->error) {
        echo 'alert("' . $this->message . '");';
    }
    ?>
</script>
</body>
</html>
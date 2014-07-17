<?php
$uploadstyle = "
    <style>
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
            position: relative;
            overflow: hidden;
        }
    </style>";
\Toy\Html\Document::singleton()->addCssBlock('uploadstyle', $uploadstyle);
?>
<?php $this->endBlock(); ?>
<?php $this->beginBlock('content'); ?>
<form id="fileupload" method="POST" action="<?php echo $this->buildUrl('icon');?>" enctype="multipart/form-data">
<span class="btn btn-success fileinput-button">
    <span><?php echo $this->localize->_('select_file')?></span>
    <input type="file" id="uploadfile" name="uploadfile" multiple="">
</span>
</form>
<script language="javascript">
    $('uploadfile').addEvent('change', function(){
       $('fileupload').submit();
    });
</script>
<?php
if($this->error){
    echo '<script language="javascript">alert("'.$this->message.'");</script>';
}
if($this->url){
    echo '<script language="javascript">';
    echo 'window.parent.uploadSuccess("icon", {url:"'.$this->url.'",width:'.$this->width.',height:'.$this->height.'}, 1);';
    echo '</script>';
}
$this->endBlock();
echo $this->includeTemplate('layout\empty');
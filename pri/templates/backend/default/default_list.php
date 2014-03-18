<?php $this->beginBlock('content'); ?>
    <ul class="breadcrumb">
        <?php
            foreach($this->breadcrumb as $item):
                if(array_key_exists('active', $item)):
        ?>
        <li class="active"><?php echo $item['text']; ?></li>
        <?php else: ?>
        <li><a href="<?php echo array_key_exists('url', $item)? $item['url']: '#' ?>"><?php echo $item['text']; ?></a></li>
        <?php
        endif;
        endforeach;
        ?>
    </ul>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?php if($this->buttons): ?>
                <div class="pull-right">
                    <div class="btn-group">
                        <?php foreach($this->buttons as $btn): ?>
                        <a href="<?php echo $btn['url']; ?>"><?php echo $btn['text'];?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="panel-body">
            <?php echo $this->renderBlock('list'); ?>
        </div>
    </div>
<?php
$this->endBlock();
echo $this->includeTemplate('master');
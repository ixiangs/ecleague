<?php $this->beginBlock('content'); ?>
<?php echo $this->includeTemplate('alert'); ?>
    <div class="row">
        <div class="widget stacked">
            <div class="widget-header">
                <ol class="breadcrumb">
                    <?php
                    foreach ($this->breadcrumb as $item):
                        if (array_key_exists('active', $item)):
                            ?>
                            <li class="active"><?php echo $item['text']; ?></li>
                        <?php else: ?>
                            <li>
                                <a href="<?php echo array_key_exists('url', $item) ? $item['url'] : '#' ?>"><?php echo $item['text']; ?></a>
                            </li>
                        <?php
                        endif;
                    endforeach;
                    ?>
                </ol>
            </div>
            <div class="widget-content">
                <div class="pull-right">
                    <?php
                    if ($this->hasBlock('toolbar')):
                        echo $this->renderBlock('toolbar');
                    else:
                        if ($this->buttons):
                            foreach ($this->buttons as $btn):
                                if(is_array($btn)):
                                    echo '<a class="btn btn-default" href="'.(array_key_exists('url', $btn) ? $btn['url'] : '#').'">'.$btn['text'].'</a>';
                                else:
                                    echo $btn->render();
                                endif;
                                echo '&nbsp;&nbsp;';
                            endforeach;
                        endif;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>

<?php if ($this->hasBlock('list')): ?>
    <div class="row">
    <div class="widget stacked widget-table action-table">
    <div class="widget-content">
    <?php echo $this->renderBlock('list'); ?>
    </div>
    </div>
    </div>
    <?php
    else:
    if ($this->datatable):
        ?>
        <div class="row">
            <div class="widget stacked widget-table action-table">
                <div class="widget-content">
                    <?php echo $this->datatable->render(); ?>
                </div>
            </div>
        </div>
    <?php
    endif;
if ($this->pagination):?>
    <div class="row">
        <?php echo $this->pagination->render(); ?>
    </div>
<?php endif;
endif;
?>
<?php
$this->endBlock();
echo $this->includeTemplate('layout\base');
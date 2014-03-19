<?php $this->beginBlock('content'); ?>
    <div id="bread" class="col-md-12">
        <div class="crumbs">
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
    </div>

<?php if ($this->hasBlock('toolbar')): ?>
    <div class="col-md-12">
        <div class="wdgt wdgt-default">
            <div class="wdgt-body">
                <?php echo $this->renderBlock('toolbar'); ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="col-md-12">
        <div class="wdgt wdgt-default">
            <div class="wdgt-body">
                <div class="align-right">
                    <?php
                    if ($this->buttons):
                        foreach ($this->buttons as $btn):
                            ?>
                            <a class="btn btn-default"
                               href="<?php echo array_key_exists('url', $btn) ? $btn['url'] : '#' ?>"><?php echo $btn['text']; ?></a>
                        <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

    <div class="col-md-12">
        <div class="wdgt">
            <div class="wdgt-body">
                <?php
                if ($this->hasBlock('form')):
                    echo $this->renderBlock('form');
                else:
                    if ($this->form):
                        echo $this->form->render();
                    endif;
                endif;
                ?>
            </div>
        </div>
    </div>
<?php
$this->endBlock();
echo $this->includeTemplate('layout\base');
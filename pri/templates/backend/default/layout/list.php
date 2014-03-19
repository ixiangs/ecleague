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

    <div class="col-md-12">
        <div class="wdgt">
            <div class="wdgt-body wdgt-table">
                <?php
                if ($this->hasBlock('list')):
                    echo $this->renderBlock('list');
                else:
                    if ($this->datatable):
                        echo $this->datatable->render();
                    endif;
                    if($this->pagination):
                        echo $this->pagination->render();
                    endif;
                endif;
                ?>
            </div>
        </div>
    </div>
<?php
$this->endBlock();
echo $this->includeTemplate('layout\base');
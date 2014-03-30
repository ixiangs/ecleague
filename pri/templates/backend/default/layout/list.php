<?php $this->beginBlock('content'); ?>
<?php echo $this->includeTemplate('alert'); ?>
    <div class="row breadcrumb-row">
        <ol class="breadcrumb col-lg-5">
            <?php
            if ($this->breadcrumb):
                $b = $this->breadcrumb;
                $lb = array_pop($b);
                foreach ($b as $item):
                    echo '<li>' . $item->getAttribute('text') . '</li>';
                endforeach;
                echo '<li class="active">' . $lb->getAttribute('text') . '</li>';
            endif;
            ?>
        </ol>
        <div class="pull-right">
            <?php
            if ($this->hasBlock('navigationBar')):
                echo $this->renderBlock('navigationBar');
            else:
                $b = $this->navigationBar;
                foreach($b as $btn){
                    $btn->setAttribute('class', 'btn btn-default');
                }
                $fb = array_shift($b);
                echo $fb->render();
                foreach ($b as $btn):
                    echo '&nbsp;&nbsp;';
                    echo $btn->render();
                endforeach;
            endif;
            ?>
        </div>
    </div>


<?php
if ($this->hasBlock('datalist')):
    echo $this->renderBlock('datalist');
else:
    ?>
    <div class="row">
        <form id="table_form" method="post">
            <div class="panel panel-default">
                <?php
                if ($this->hasBlock('toolbar') || $this->toolbar):
                    echo '<div class="panel-heading text-right">';

                    if ($this->hasBlock('toolbar')):
                        echo $this->renderBlock('toolbar');
                    endif;
                    if ($this->toolbar):
                        foreach ($this->toolbar as $act):
                            echo '&nbsp;&nbsp;';
                            echo $act->render();
                        endforeach;
                    endif;
                    echo '</div>';
                endif;
                echo $this->datatable->render();
                ?>
            </div>
        </form>
    </div>
    <?php
    if ($this->pagination):
        echo '<div class="row text-right">';
        echo $this->pagination->setAttribute('class', 'pagination pagination-lg')->render();
        echo '</div>';
    endif;
endif;
?>
<?php
$this->endBlock();
echo $this->includeTemplate('layout\base');
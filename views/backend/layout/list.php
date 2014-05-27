<?php $this->beginBlock('content'); ?>
<?php echo $this->includeTemplate('alert'); ?>
    <div class="row breadcrumb-row">
        <div class="col-md-6">
            <?php echo $this->renderBreadcrumbs(); ?>
        </div>
        <div class="pull-right">
            <?php
            if ($this->hasBlock('navigationBar')):
                echo $this->renderBlock('navigationBar');
            else:
                $b = $this->navigationBar;
                foreach ($b as $btn) {
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
                <?php
                if ($this->hasBlock('toolbar') || $this->toolbar):
                    echo '<div class="panel panel-default"><div class="panel-heading text-right">';

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
                if ($this->hasBlock('toolbar') || $this->toolbar):
                    echo '</div>';
                endif;
                ?>
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
<?php $this->beginBlock('content'); ?>
<?php echo $this->includeTemplate('alert'); ?>
    <div class="row breadcrumb-row">
        <ol class="breadcrumb col-md-6">
            <?php
            if ($this->breadcrumb):
                foreach ($this->breadcrumb as $item):
                    echo '<li>' . $item->render() . '</li>';
                endforeach;
            else:
                $requestComponent = \Toy\Web\Application::getRequestComponent();
                $breadcrumbs = $requestComponent->getActionBreadcrumb();
                if($breadcrumbs):
                    foreach($breadcrumbs as $item):
                        $text = $item['text'];
                        if($text[0] == '@'):
                            $text = $this->locale->_(substr($text, 1));
                        endif;
                        echo '<li>' . $text . '</li>';
                    endforeach;
                endif;
            endif;
            ?>
        </ol>
        <div class="pull-right">
            <?php
            if ($this->hasBlock('navigationBar')):
                echo $this->renderBlock('navigationBar');
            elseif($this->navigationBar):
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
                if($this->tableHiddens):
                    foreach($this->tableHiddens as $th):
                        echo $th->render();
                    endforeach;
                endif;
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
<?php
$this->beginBlock('content');
echo $this->includeTemplate('alert');
if ($this->hasBlock('datalist')):
    echo $this->renderBlock('datalist');
else:
    echo '<form id="table_form" method="post">';
    echo '<div class="row">';
    echo '<div class="col-md-12">';
    echo '<div class="widget">';
    if ($this->hasBlock('toolbar')):
        echo '<div class="widget-head"><div class="pull-right">';
        echo $this->renderBlock('toolbar');
        echo '</div><div class="clearfix"></div></div>';
    endif;
    if ($this->toolbar):
        echo '<div class="widget-head"><div class="pull-right">';
        foreach ($this->toolbar as $item):
            echo $item->render();
        endforeach;
        echo '</div><div class="clearfix"></div></div>';
    endif;
    echo '<div class="widget-content">';
    echo $this->datatable->render();
    echo '</div>';
    if ($this->pagination):
        echo '<div class="widget-foot">';
        echo $this->pagination->setAttribute('class', 'pagination pull-right')->render();
        echo '<div class="clearfix"></div></div>';
    endif;
    echo '</div></div></div></form>';
endif;
$this->endBlock();
echo $this->includeTemplate('layout\base');
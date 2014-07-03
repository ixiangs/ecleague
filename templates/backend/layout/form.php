<?php
$this->beginBlock('content');
echo $this->includeTemplate('alert');
if ($this->hasBlock('datalist')):
    echo $this->renderBlock('datalist');
else:
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
            $class = $item->getAttribute('class');
            echo $item->setAttribute('class', $class.' pull-right')->render();
        endforeach;
        echo '</div><div class="clearfix"></div></div>';
    endif;
    echo '<div class="widget-content"><div class="padd">';
    if ($this->hasBlock('form')):
        echo $this->renderBlock('form');
    else:
        if ($this->form):
            echo $this->form->render();
        endif;
    endif;
    echo '</div></div></div></div></div>';
endif;
$this->endBlock();
echo $this->includeTemplate('layout\base');
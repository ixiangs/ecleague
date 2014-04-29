<?php $this->beginBlock('content'); ?>
<?php echo $this->includeTemplate('alert'); ?>
    <div class="row breadcrumb-row">
        <ol class="breadcrumb col-lg-5">
            <?php
            if ($this->breadcrumb):
                $b = $this->breadcrumb;
                $lb = array_pop($b);
                foreach ($b as $item):
                    echo '<li>' . $item->render() . '</li>';
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

<div class="row">
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
        ?>
        <div class="panel-body">
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
<?php
echo $this->renderBlock('others');
$this->endBlock();
echo $this->includeTemplate('layout\base');
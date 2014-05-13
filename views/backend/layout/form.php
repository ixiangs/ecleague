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
            if ($this->hasBlock('toolbar')):
                echo $this->renderBlock('toolbar');
            endif;
            if ($this->toolbar):
                foreach ($this->toolbar as $act):
                    echo '&nbsp;&nbsp;';
                    echo $act->render();
                endforeach;
            endif;
            ?>
        </div>
    </div>

    <div class="row">
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

<?php
echo $this->renderBlock('others');
$this->endBlock();
echo $this->includeTemplate('layout\base');
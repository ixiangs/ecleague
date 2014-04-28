<?php $this->beginBlock('content');
$langId = $this->locale->getCurrentLanguageId();
?>
    <div class="row breadcrumb-row">
        <ol class="breadcrumb col-md-6">
            <li><?php echo $this->locale->_('attrs_manage'); ?></li>
            <li><?php echo $this->locale->_('attrs_attribute_set'); ?></li>
            <li><?php echo $this->component->getName(); ?></li>
            <li class="active"><?php echo $this->model->name[$langId]; ?></li>
        </ol>
        <div class="pull-right">
            <?php
            echo $this->html->anchor($this->locale->_('attrs_new_group'),
                                     $this->router->buildUrl(
                                         'attribute-group/add',
                                         array('component_id'=>$this->model->getComponentId(), 'set_id'=>$this->model->getId())))
                ->setAttribute('class', 'btn btn-default')
                ->render();
            echo '&nbsp;&nbsp;';
            echo $this->html->anchor($this->locale->_('attrs_new_attribute'), $this->router->buildUrl('attribute/type',
                array('component_id'=>$this->model->getComponentId(), 'set_id'=>$this->model->getId())))
                ->setAttribute('class', 'btn btn-default')
                ->render();
            echo '&nbsp;&nbsp;';
            echo $this->html->button('button', $this->locale->_('attrs_new_attribute'))
                    ->setEvent('click', 'loadingModal.show();')
                    ->render();
            ?>
        </div>
    </div>
    <div class="row">
        <form id="form1" method="post">
        <div class="panel panel-default">
            <div class="panel-heading  text-right">
                <?php echo $this->html->button('button', $this->locale->_('save'), 'btn btn-primary')->setAttribute('id', 'save')->render(); ?>
            </div>
            <div class="panel-body">
                <div class="col-md-8 column">
                    <?php foreach ($this->groups as $group): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-list fa-fw"></i><?php echo $group->name[$langId] ?>
                                <div class="pull-right">
                                    <a href="<?php echo $this->router->buildUrl('attribute-group/edit', array('id'=>$group->getId(), 'component_id'=>$this->model->getComponentId(), 'set_id'=>$this->model->getId()));?>">
                                        <?php echo $this->locale->_('edit'); ?>
                                    </a>
                                    &nbsp;
                                    <a href="javascript:void(0);" class="delete-group">
                                        <?php echo $this->locale->_('delete'); ?>
                                    </a>
                                </div>
                            </div>
                            <div class="panel-body">
                                <ul class="sortable attribute-group" data-id="<?php echo $group->getId(); ?>">
                                    <?php
                                    $sortAttributeIds = $group->getAttributeIds();
                                    foreach($sortAttributeIds as $aid):
                                    foreach ($group->getAttributes() as $attr):
                                        if($attr->getId() == $aid):
                                    ?>
                                        <li class="ui-state-default attribute" data-id="<?php echo $attr->getId(); ?>">
                                            <i class="fa fa-list fa-fw"></i>
                                            <?php echo $attr->display_text[$langId];?>
                                            <div class="pull-right">
                                            <a href="<?php echo $this->router->buildUrl('attribute/edit',
                                                    array('id'=>$attr->getId(), 'component_id'=>$this->model->getComponentId(), 'set_id'=>$this->model->getId()));
                                            ?>"><?php echo $this->locale->_('edit'); ?></a>
                                        &nbsp;
                                        <a href="javascript:void(0);" class="delete-attribute"><?php echo $this->locale->_('delete'); ?></a>
                                        </div>
                                        </li>
                                    <?php
                                            endif;
                                        endforeach;
                                    endforeach;
                                    ?>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo $this->locale->_('attrs_attribute_list'); ?>
                        </div>
                        <div class="panel-body">
                            <ul id="unselected-attribute" class="sortable">
                                <?php foreach ($this->unselectedAttributes as $attr): ?>
                                    <li class="ui-state-default attribute" data-id="<?php echo $attr->getId(); ?>">
                                        <i class="fa fa-list fa-fw"></i>
                                        <?php echo $attr->display_text[$langId];
                                        echo '<div class="pull-right">';
                                        echo $this->html->anchor($this->locale->_('edit'),
                                            $this->router->buildUrl(
                                                'attribute/edit',
                                                array('id'=>$attr->getId(), 'component_id'=>$this->model->getComponentId(), 'set_id'=>$this->model->getId()))
                                        )->render();
                                        echo '</div>';
                                        ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
<?php
$this->nextBlock('footerjs');
?>
    <script language="javascript">
        var loadingModal = null;
        $(document).ready(function () {
            loadingModal = new Toy.Widget.Loading();
            $(".sortable").sortable({
                handle: '.fa',
                connectWith: ".sortable",
                dropOnEmpty: true
            });
            $(".column").sortable({
                handle: '.fa'
            });
            $('.delete-attribute').click(function(){
                $(this).parent().parent().appendTo('#unselected-attribute');
            });
            $('.delete-group').click(function(){
                $(this).parent().parent().parent().find('li.attribute').each(function(){
                   $(this).appendTo('#unselected-attribute');
                });
               $(this).parent().parent().parent().remove();
            });
            $('#save').click(function(){
                var passed = true;
                var $f = $('#form1');
                $('#form1 input[type="hidden"]').remove();
                $('.attribute-group').each(function(){
                    var values = [];
                    $(this).find('.attribute').each(function(){
                        values.push($(this).attr('data-id'));
                    });
                    if(values.length == 0){
                        passed = false;
                    }
                    $f.append('<input type="hidden" name="groups[]" value="' + $(this).attr('data-id') + '"/>');
                    for(var i = 0; i < values.length; i++){
                        $f.append('<input type="hidden" name="attributes[' + $(this).attr('data-id') + '][]" value="' + values[i] + '"/>');
                    }
                });

                if(!passed){
                    alert('<?php echo $this->locale->_('attrs_err_group_not_empty'); ?>');
                }else{
                    $f.submit();
                }
            });
        });
    </script>
<?php
$this->endBlock();
echo $this->includeTemplate('layout\base');
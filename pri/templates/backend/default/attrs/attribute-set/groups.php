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
                <div id="selected_group" class="col-md-8 column">
                    <?php foreach ($this->groups as $group): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-list fa-fw"></i><?php echo $group->name[$langId] ?>
                                <div class="pull-right">
                                    <a href="javascript:void(0);" class="delete-group" data-id="<?php echo $group->getId(); ?>" data-title="<?php echo $group->name[$langId]; ?>">
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
                            <?php echo $this->locale->_('attrs_attribute_group'); ?>
                        </div>
                        <div class="panel-body">
                            <ul id="unselected_group" class="sortable">
                                <?php foreach ($this->unselectedGroups as $group): ?>
                                    <li class="ui-state-default group" data-locked="<?php echo $group->getLocked()?>" data-group-ids="<?php echo implode(',', $group->getAttributeIds()); ?>" data-id="<?php echo $group->getId(); ?>">
                                        <?php echo $group->name[$langId]; ?>
                                        <div class="pull-right">
                                            <a href="javascript:void(0);" class="select-group"><?php echo $this->locale->_('select'); ?></a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo $this->locale->_('attrs_attribute_list'); ?>
                        </div>
                        <div class="panel-body">
                            <ul id="unselecte_attribute" class="sortable">
                                <?php foreach ($this->unselectedAttributes as $attr): ?>
                                    <li class="ui-state-default attribute" data-id="<?php echo $attr->getId(); ?>">
                                        <i class="fa fa-list fa-fw"></i>
                                        <?php echo $attr->display_text[$langId];?>
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
        function sort(){
            $(".sortable").sortable({
                handle: '.fa',
                connectWith: ".sortable",
                dropOnEmpty: true
            });
            $(".column").sortable({
                handle: '.fa'
            });
        }
        $(document).ready(function () {
            sort();
            $('#selected_group').delegate('.delete-attribute', 'click', function(){
                $(this).parent().parent().appendTo('#unselected-attribute');
            });

            $('#selected_group').delegate('.delete-group', 'click', function(){
                $this = $(this);
                var tpl = '<li class="ui-state-default group" data-id="' + $this.attr('data-id') + '">'
                        + $this.attr('data-title') + '</li>';
                $('#unselected_group').append(tpl);
                $(this).parent().parent().parent().find('li.attribute').each(function(){
                   $(this).appendTo('#unselected-attribute');
                });
               $(this).parent().parent().parent().remove();
            });

            $('#unselected_group').delegate('.select-group', 'click', function(){
                var $this = $(this);
                var gid = $this.parent().parent().attr('data-id')
                new Toy.Request()
                    .addEvent('ready', function(){
                        Toy.Widget.ProgressModal.show();
                    }).addEvent('success', function(data, status, xhr){
                        var json = data;
                        var tpl = '<div class="panel panel-default">'
                                + '<div class="panel-heading">'
                                + '<i class="fa fa-list fa-fw"></i>' + json.name + '<div class="pull-right">'
                                + '<a href="javascript:void(0);" class="delete-group">'
                                + '<?php echo $this->locale->_('delete'); ?>'
                                + '</a></div></div><div class="panel-body">'
                                + '<ul class="sortable attribute-group ui-sortable" data-id="' + json.id + '">';
                        json.attributes.each(function(item){
                            tpl += '<li class="ui-state-default attribute" data-id="' + item.id + '">'
                                + '<i class="fa fa-list fa-fw"></i>' + item.name + '</li>';
                        });
                        tpl += '</ul></div></div>';
                        $('.column').append(tpl);
                        Toy.Widget.ProgressModal.hide();
                        $this.parent().parent().remove();
                        sort();
                    }).get('<?php echo $this->router->buildUrl('group'); ?>', {'id':gid});
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
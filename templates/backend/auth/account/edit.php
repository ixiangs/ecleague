<?php
$this->assign('toolbar', array(
    $this->html->anchor($this->localize->_('back'), $this->router->findHistory('list'))
        ->setAttribute('class', 'btn btn-default'),
    $this->html->button('button', $this->localize->_('save'), 'btn btn-primary')->setAttribute('data-submit', 'form1')
));

$f = $this->html->form()
    ->setAttribute('action', $this->router->buildUrl('save'));
if ($this->router->action == 'add'):
    $f->newField($this->localize->_('username'), true,
        $this->html->textbox('username', 'data[username]', $this->model->getUsername())
            ->addValidateRule('required', true));
else:
    $f->addStaticField($this->localize->_('username'), $this->model->getUsername());
endif;
$f->newField($this->localize->_('email'), true,
    $this->html->textbox('email', 'data[email]', $this->model->getEmail(), 'email')
        ->addValidateRule('required', true));
if ($this->router->action == 'add'):
    $f->newField($this->localize->_('password'), true,
        $this->html->textbox('password', 'data[password]', '')
            ->addValidateRule('required', true));
endif;
$f->newField($this->localize->_('status'), true,
    $this->html->select('status', 'data[status]', $this->model->getStatus(), array(
        \Components\Auth\Constant::STATUS_ACCOUNT_ACTIVATED => $this->localize->_('auth_status_activated'),
        \Components\Auth\Constant::STATUS_ACCOUNT_NONACTIVATED => $this->localize->_('auth_status_nonactivated'),
        \Components\Auth\Constant::STATUS_ACCOUNT_DISABLED => $this->localize->_('disabled')
    )));
$f->newField($this->localize->_('auth_group'), true,
    $this->html->select('group_id', 'data[group_id]', $this->model->getGroupId(), $this->groups));
$f->newField($this->localize->_('auth_role_list'), true,
    $this->html->optionList('role_ids', 'data[role_ids][]', $this->model->getRoleIds(), $this->roles)
        ->addValidateRule('required', true));
$domains = \Toy\Util\ArrayUtil::toArray(\Toy\Web\Configuration::$domains, function ($item) {
    return array($item->getName(), $item->getName());
});

$f->newField($this->localize->_('auth_domain'), true,
    $this->html->optionList('domains', 'data[domains][]', $this->model->getDomains(), $domains)
        ->addValidateRule('required', true));
$f->addHidden('id', 'data[id]', $this->model->getId());
$this->assign('form', $f);
$this->beginScript('account');
?>
    <script language="JavaScript">
        $('group_id').addEvent('change', function () {
            if (this.get('value') == '1') {
                $$('input[name="data[role_ids][]"](0)')
                    .getParent('.form-group').setStyle('display', 'none');
                $$('input[name="data[domains][]"](0)')
                    .getParent('.form-group').setStyle('display', 'none');
                $$('input[name="data[role_ids][]"], input[name="data[domains][]"]').each(function(item){
                   item.removeProperty('data-validate-required');
                });
            } else {
                $$('input[name="data[role_ids][]"](0)')
                    .getParent('.form-group').setStyle('display', 'block');
                $$('input[name="data[domains][]"](0)')
                    .getParent('.form-group').setStyle('display', 'block');
                $$('input[name="data[role_ids][]"], input[name="data[domains][]"]').each(function(item){
                    item.set('data-validate-required', 'true');
                });
            }
            $('form1').retrieve('validator').reload();
        });
        window.addEvent('domready', function(){
            if ($('group_id').get('value') == '1') {
                $$('input[name="data[role_ids][]"](0)')
                    .getParent('.form-group').setStyle('display', 'none');
                $$('input[name="data[domains][]"](0)')
                    .getParent('.form-group').setStyle('display', 'none');
                $$('input[name="data[role_ids][]"], input[name="data[domains][]"]').each(function(item){
                    item.removeProperty('data-validate-required');
                });
                $('form1').retrieve('validator').reload();
            }
        });
    </script>
<?php
$this->endScript();
echo $this->includeTemplate('layout\form');
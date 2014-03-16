{% extends "../../master.html" %}
{% block content %}
<ul class="breadcrumb">
    <li>
        <a href="#">{{ _("auth_manage") }}</a>
    </li>
    <li>
        <a href="{{ controller_url }}/list">{{ _("auth_behavior_list") }}</a>
    </li>
    <li class="active">
        {% if handler.request_action is "add" %}
        {{ _("add") }}
        {% else %}
        {{ _("edit") }}
        {% end %}
    </li>
</ul>

<div class="col-md-4">
    <form id="form1" method="post">
        {% raw begin_form() %}
        {% raw field_text(_("code"), "@code#code%required|letter", model.code) %}
        {% raw field_text(_("name"), "@name#name%required", model.name) %}
        {% raw field_text(_("url"), "@url#url", model.url) %}
        {% raw field_boolean(_("enable"), "@enabled#enabled", model.enabled) %}
        <div class="form-group">
            <button type="submit" class="btn btn-default btn-primary">{{ _("save") }}</button>
            <a href="{{ handler.referer_url }}" class="btn btn-default">{{ _("back") }}</a>
        </div>
        {% raw end_form() %}
        <input type="hidden" id="id" name="id" value="{{ model.id or '' }}"/>
        {% raw referer_hidden() %}
    </form>
</div>
{% end %}
{% block footerjs %}
<script type="text/javascript">
    var validator = new Toys.Html.Validation.Validator('#form1');
</script>
{% end %}
{*
 * Copyright (c) 2004-2015 OIC Group, Inc.
 *
 * This file is part of Exponent
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 *}

{css unique="product-edit" link="`$asset_path`css/product_edit.css" corecss="admin-global,tree,panels"}

{/css}

{if $smarty.const.SITE_WYSIWYG_EDITOR == "ckeditor"}
{script unique="ckeditor" src="`$smarty.const.PATH_RELATIVE`external/editors/ckeditor/ckeditor.js"}

{/script}
{elseif $smarty.const.SITE_WYSIWYG_EDITOR == "tinymce"}
{script unique="tinymcepu" src="`$smarty.const.PATH_RELATIVE`external/editors/tinymce/plugins/quickupload/plupload.full.min.js"}

{/script}
{script unique="tinymce" src="`$smarty.const.PATH_RELATIVE`external/editors/tinymce/tinymce.min.js"}

{/script}
{/if}

<div id="editproduct" class="module store edit yui3-skin-sam exp-admin-skin">
    {if $record->id != ""}
        <h1>{'Edit Information for'|gettext}{if $record->childProduct|@count != 0} {'Parent'|gettext}{/if}{if $record->parent_id != 0} {'Child'|gettext}{/if} {$model_name|ucfirst}</h1>
    {else}
        <h1>{'New'|gettext} {$model_name}</h1>
    {/if}
    <blockquote>
        {if $record->parent_id == 0}
            {if $record->childProduct|count}
                <strong>{'Child Products:'|gettext}</strong>
                <ul>
                {foreach from=$record->childProduct item=child}
                    <li><a href="{link controller='store' action='edit' id=$child->id}" title="{$child->model}">{$child->title}</a></li>
                {/foreach}
                </ul>
            {/if}
        {else}
            <strong>{'Parent Product:'|gettext}</strong> <a href="{link controller='store' action='edit' id=$record->parent_id}" title="{$parent->model}">{$parent->title}</a>
        {/if}
    </blockquote>
    {form action=update}
        {control type="hidden" name="id" value=$record->id}
		<!-- if it copied -->
		{if $record->original_id}
		{control type="hidden" name="original_id" value=$record->original_id}
		{/if}
        <div id="editproduct-tabs" class="yui-navset exp-skin-tabview hide">
            <ul id="dynamicload" class="exp-ajax-tabs yui-nav">
                <li><a href="{link action="edit" product_type="product" ajax_action=1 id=$record->id parent_id = $record->parent_id view="edit_general"}">{'General'|gettext}</a></li>
                <li><a href="{link action="edit" product_type="product" ajax_action=1 id=$record->id parent_id = $record->parent_id view="edit_pricing"}">{'Pricing, Tax'|gettext} &amp; {'Discounts'|gettext}</a></li>
                <li><a href="{link action="edit" product_type="product" ajax_action=1 id=$record->id parent_id = $record->parent_id view="edit_images"}">{'Images'|gettext} &amp; {'Files'|gettext}</a></li>
                <li><a href="{link action="edit" product_type="product" ajax_action=1 id=$record->id parent_id = $record->parent_id view="edit_quantity"}">{'Quantity'|gettext}</a></li>
                <li><a href="{link action="edit" product_type="product" ajax_action=1 id=$record->id parent_id = $record->parent_id view="edit_shipping"}">{'Shipping'|gettext}</a></li>
                <li><a href="{link action="edit" product_type="product" ajax_action=1 id=$record->id parent_id = $record->parent_id view="edit_categories"}">{'Categories'|gettext}</a></li>
                <li><a href="{link action="edit" product_type="product" ajax_action=1 id=$record->id parent_id = $record->parent_id view="edit_options"}">{'Options'|gettext}</a></li>
                {if $record->parent_id == 0}
                    <li><a href="{link action="edit" product_type="product" ajax_action=1 id=$record->id view="edit_featured"}">{'Featured'|gettext}</a></li>
                    <li><a href="{link action="edit" product_type="product" ajax_action=1 id=$record->id view="edit_related"}">{'Related Products'|gettext}</a></li>
                {/if}
                <li><a href="{link action="edit" product_type="product" ajax_action=1 id=$record->id parent_id = $record->parent_id view="edit_userinput"}">{'User Input'|gettext}</a></li>
                <li><a href="{link action="edit" product_type="product" ajax_action=1 id=$record->id parent_id = $record->parent_id view="edit_status"}">{'Active'|gettext} &amp; {'Status Settings'|gettext}</a></li>
                {if $record->parent_id == 0}
                    <li><a href="{link action="edit" product_type="product" ajax_action=1 id=$record->id view="edit_meta"}">{'SEO'|gettext}</a></li>
                {/if}
                <li><a href="{link action="edit" product_type="product" ajax_action=1 id=$record->id parent_id = $record->parent_id view="edit_notes"}">{'Notes'|gettext}</a></li>
                <li><a href="{link action="edit" product_type="product" ajax_action=1 id=$record->id parent_id = $record->parent_id view="edit_extrafields"}">{'Extra Fields'|gettext}</a></li>
                {if $record->parent_id == 0}
                    <li><a href="{link action="edit" product_type="product" ajax_action=1 id=$record->id view="edit_model"}">{'SKUS/Model'|gettext}</a></li>
                {/if}
                <li><a href="{link action="edit" product_type="product" ajax_action=1 id=$record->id parent_id = $record->parent_id view="edit_misc"}">{'Misc'|gettext}</a></li>
            </ul>
            <div id="loadcontent" class="exp-ajax-tabs-content yui-content yui3-skin-sam"></div>
        </div>
        <div id="loading" class="loadingdiv">{"Loading"|gettext} {"Product Edit Form"|gettext}</div>
        {control type="buttongroup" submit="Save Product"|gettext cancel="Cancel"|gettext}
        {if isset($record->original_id)}
            {control type="hidden" name="original_id" value=$record->original_id}
            {control type="hidden" name="original_model" value=$record->original_model}
            {control type="checkbox" name="copy_children" label="Copy Child Products?"|gettext value="1"}
            {control type="checkbox" name="copy_related" label="Copy Related Products?"|gettext value="1"}
            {control type="checkbox" name="adjust_child_price" label="Reset Price on Child Products?"|gettext value="1"}
            {control type="text" name="new_child_price" label="New Child Price"|gettext value=""}
        {/if}
    {/form}
</div>

{script unique="prodtabs" yui3mods="get,exptabs,tabview,node-load,event-simulate,cookie" jquery='Sortable,SimpleAjaxUploader,jstree'}
{literal}
    EXPONENT.YUI3_CONFIG.modules.exptabs = {
        fullpath: EXPONENT.JS_RELATIVE+'exp-tabs.js',
        requires: ['history','tabview','event-custom']
    };

    YUI(EXPONENT.YUI3_CONFIG).use('*', function(Y) {
       
//       var lastTab = !Y.Lang.isNull(Y.Cookie.get("edit-tab")) ? Y.Cookie.get("edit-tab") : 0;
       var tabs = Y.all('#dynamicload li a');
       var cdiv = Y.one('#loadcontent');

       // initialize each tab container as empty
       tabs.each(function(n, k){
           cdiv.append('<div id="exptab-'+k+'" class="exp-ajax-tab"></div>');
       });

       var cTabs = cdiv.all('.exp-ajax-tab');

       // load the selected tab
       var loadTab = function (e){
           e.halt();
           var tab = e.currentTarget;
           var tIndex = tabs.indexOf(tab);
           var cTab = cTabs.item(tIndex);
           var puri =  tab.getAttribute('href');

//           Y.Cookie.set("edit-tab", tIndex);
           
           tabs.removeClass('current');
           tab.addClass('current');
           cTabs.hide();
           if (!cTab.hasChildNodes()) {  // if the tab is empty
               cTab.load(puri, parseScripts);  // load the tab and process the content
           };
           cTab.show();
       }

       // process the scripts and css links
       var parseScripts = function (id, o){
           // process the javascript
           this.all('script').each(function(n){
               if(!n.get('src')){
                   // execute inline code
                   eval(n.get('innerHTML'));
               } else {
                   // attach script src link
                   var url = n.get('src');
                   Y.Get.script(url);
               };
           });
           // css
           //Y.log(tab.all('.io-execute-response link'));
           // attach the stylesheets to the page
           this.all('link').each(function(n){
               var url = n.get('href');
               Y.Get.css(url);
           });
       }

       // load the tab when it's clicked on
       tabs.on('click',loadTab);

       // load all the tabs if we are copying in order to save all the data
       if ({/literal}{if $copy}1{else}0{/if}{literal}) {
           tabs.each(function(n, k){
               n.simulate('click');
           });
       };
       // click on the 1st tab initially
//       tabs.item(lastTab).simulate('click');
       tabs.item(0).simulate('click');

       Y.one('#editproduct-tabs').removeClass('hide');
       Y.one('.loadingdiv').remove();
    });
{/literal}
{/script}

{*{script unique="tabload" jquery=1 bootstrap="tab,transition"}*}
{*{literal}*}
    {*$('.loadingdiv').remove();*}

    {*$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {*}
        {*var target = $(e.target).attr("href") // activated tab*}
        {*if ($(target).is(':empty')) {*}
            {*$.ajax({*}
                {*type: "GET",*}
                {*url: target,*}
                {*error: function(data){*}
                    {*alert("There was a problem");*}
                {*},*}
                {*success: function(data){*}
                    {*$(target).html(data);*}
                {*}*}
            {*})*}
        {*}*}
    {*})*}
{*{/literal}*}
{*{/script}*}

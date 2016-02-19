{*
 * Copyright (c) 2004-2016 OIC Group, Inc.
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

{uniqueid prepend="cal" assign="name"}

{css unique="announcement" link="`$asset_path`css/announcement.css"}

{/css}

<div class="module events announcement">
    {if $moduletitle && !($config.hidemoduletitle xor $smarty.const.INVERT_HIDE_TITLE)}<{$config.heading_level|default:'h1'}>{/if}
    {rss_link}
    {if $moduletitle && !($config.hidemoduletitle xor $smarty.const.INVERT_HIDE_TITLE)}{$moduletitle}</{$config.heading_level|default:'h1'}>{/if}

    <div class="module-actions">
        {if !$config.disable_links}
            {icon class="monthviewlink" action=showall time=$time text='Calendar View'|gettext}
        {/if}
        {permissions}
            {if $permissions.manage}
                &#160;&#160;|&#160;&#160;
                {icon class="adminviewlink" action=showall view=showall_Administration time=$time text='Administration View'|gettext}
                {if !$config.disabletags}
                    &#160;&#160;|&#160;&#160;
                    {icon controller=expTag class="manage" action=manage_module model='event' text="Manage Tags"|gettext}
                {/if}
                {if $config.usecategories}
                    &#160;&#160;|&#160;&#160;
                    {icon controller=expCat action=manage model='event' text="Manage Categories"|gettext}
                {/if}
            {/if}
        {/permissions}
        {printer_friendly_link text='Printer-friendly'|gettext prepend='&#160;&#160;|&#160;&#160;'}
        {export_pdf_link prepend='&#160;&#160;|&#160;&#160;'}
    </div>
    {if $config.moduledescription != ""}
   		{$config.moduledescription}
   	{/if}
    {$myloc=serialize($__loc)}
    {permissions}
        <div class="module-actions">
            {if $permissions.create}
                {icon class=add action=edit title="Add a New Event"|gettext text="Add an Event"|gettext}
            {/if}
        </div>
    {/permissions}
    {foreach from=$items item=item}
        <div class="vevent item announcement{if $item->is_featured} featured{/if}">
            {if !empty($item->expFile[0]->url)}
                <div class="image photo" style="margin: 1em 0;padding:10px;float:left;overflow: hidden;">
                    {img file_id=$item->expFile[0]->id title="`$item->title`" class="large-img" id="enlarged-image"}
                    {clear}
                </div>
            {/if}
            <{$config.item_level|default:'h2'} class="event-info event-date">
                {if $item->is_cancelled}<span class="cancelled-label">{'This Event Has Been Cancelled!'|gettext}</span>{br}{/if}
                <a class="itemtitle{if $item->is_cancelled} cancelled{/if}{if !empty($item->color)} {$item->color}{/if}"
                    {if substr($item->location_data,1,8) != 'calevent'}
                        href="{if $item->location_data != 'event_registration'}{link action=show date_id=$item->date_id}{else}{link controller=eventregistration action=show title=$item->title}{/if}"
                    {/if}
                    ><span class="summary">{$item->title}</span> {br}
                    {if $item->is_allday == 1}
                        <span class="dtstart">{$item->eventstart|format_date}<span class="value-title" title="{date('c',$item->eventstart)}"></span></span>
                     {else}
                        <span class="dtstart">{$item->eventstart|format_date} @ {$item->eventstart|format_date:"%l:%M %p"}<span class="value-title" title="{date('c',$item->eventstart)}"></span></span>
                     {/if}
                </a>
            </{$config.item_level|default:'h2'}>
            <span class="hide">
                {'Location'|gettext}:
                <span class="location">
                    {$smarty.const.ORGANIZATION_NAME}
                </span>
                {if !empty($item->event->expCat[0]->title)}<span class="category">{$item->event->expCat[0]->title}</span>{/if}
            </span>
            {permissions}
                {if substr($item->location_data,0,3) == 'O:8'}
                    <div class="item-actions">
                        {if $permissions.edit || ($permissions.create && $item->poster == $user->id)}
                            {if $myloc != $item->location_data}
                                {if $permissions.manage}
                                    {icon action=merge id=$item->id title="Merge Aggregated Content"|gettext}
                                {else}
                                    {icon img='arrow_merge.png' title="Merged Content"|gettext}
                                {/if}
                            {/if}
                            {icon action=edit record=$item}
                        {/if}
                        {if $permissions.delete || ($permissions.create && $item->poster == $user->id)}
                            {icon action=delete record=$item}
                        {/if}
                    </div>
                {/if}
            {/permissions}
            <div class="bodycopy">
                {$item->body}
            </div>
            {if !empty($feedback_form)}
                {toggle unique=$name|cat:$item->id collapsed=1 title='Click to open'|gettext|cat:' '|cat:$feedback_form}
                    {include file="email/$feedback_form.tpl"}
                {/toggle}
            {/if}
            {clear}
        </div>
    {/foreach}
</div>

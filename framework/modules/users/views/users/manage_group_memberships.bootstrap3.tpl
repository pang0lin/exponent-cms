{*
 * Copyright (c) 2004-2014 OIC Group, Inc.
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
 
{*{css unique="group" corecss="tables"}*}

{*{/css}*}
{css unique="manage-groups" corecss="datatables-tools"}

{/css}

<div class="module users manage-group-memberships">
    <div class="info-header">
        <div class="related-actions">
			{help text="Get Help with"|gettext|cat:" "|cat:("Managing Group Memberships"|gettext) module="manage-group-members"}
        </div>
		<h2>{"Manage Group Memberships"|gettext}</h2>
    </div>

    {form action="update_memberships"}
        <input type="hidden" name="id" value="{$group->id}"/>
        {*{pagelinks paginate=$page top=1}*}
        <table id="groups-manage">
            <thead>
                <tr>
                    {*{$page->header_columns}*}
                    <th data-class="expand">{'Username'|gettext}</th>
                    <th data-hide="phone" data-name="First">{'First Name'|gettext}</th>
                    <th data-hide="phone" data-name="Last">{'Last Name'|gettext}</th>
                    <th data-name="Member">{'Is Member'|gettext}</th>
                    <th data-hide="phone" data-name="Admin">{'Is Admin'|gettext}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$page->records item=user name=listings}
                    <tr>
                        <td>{$user->username}</td>
                        <td>{$user->firstname}</td>
                        <td>{$user->lastname}</td>
                        <td>
                            {control type=checkbox name="memdata[`$user->id`][is_member]" value=1 checked=$user->is_member}
                        </td>
                        <td>
                            {control type=checkbox name="memdata[`$user->id`][is_admin]" value=1 checked=$user->is_admin}
                        </td>
                    </tr>
                {foreachelse}
                    <td colspan="5">{'No Data'|gettext}.</td>
                {/foreach}
            </tbody>
        </table>
        {*{pagelinks paginate=$page bottom=1}*}
        {control type="buttongroup" submit="Save Memberships"|gettext cancel="Cancel"|gettext}
    {/form}
</div>

{script unique="manage-groups" jquery='jquery.dataTables,dataTables.tableTools,dataTables.bootstrap3,datatables.responsive'}
{literal}
    $(document).ready(function() {
        var responsiveHelper;
        var breakpointDefinition = {
            tablet: 1024,
            phone : 480
        };
        var tableContainer = $('#groups-manage');

        var table = tableContainer.DataTable({
            columns: [
                null,
                null,
                null,
                { searchable: false, sortable: false },
                { searchable: false, sortable: false },
            ],
            autoWidth: false,
            preDrawCallback: function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper) {
                    responsiveHelper = new ResponsiveDatatablesHelper(tableContainer, breakpointDefinition);
                }
            },
            rowCallback: function (nRow) {
                responsiveHelper.createExpandIcon(nRow);
            },
            drawCallback: function (oSettings) {
                responsiveHelper.respond();
            }
        });
        var tt = new $.fn.dataTable.TableTools( table, { sSwfPath: EXPONENT.JQUERY_RELATIVE+"addons/swf/copy_csv_xls_pdf.swf" } );
        $( tt.fnContainer() ).insertBefore('div.dataTables_wrapper');
    } );
{/literal}
{/script}

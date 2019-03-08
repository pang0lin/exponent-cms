{*
 * Copyright (c) 2004-2019 OIC Group, Inc.
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

{*permissions}
    {if $permissions.show*}
        <div class="module storeadmin show">
	        <h1>{$moduletitle|default:"e-Commerce Administration"|gettext}</h1>
	        <ul>
	            <li><a href="{link action=options}">{'Manage Product Options'|gettext}</a></li>
	            <li><a href="{link action=groupdiscounts}">{'Group Discounts'|gettext}</a></li>
	            <li><a href="{link controller=billing action=showall_calculators}">{'Billing Methods'|gettext}</a></li>
	        </ul>
        </div>
    {*/if*}
{*/permissions*}


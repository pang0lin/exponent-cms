{icon class="manage" controller="storeCategory" action="manage"}
{control type="hidden" name="categories_tab_loaded" value=1}    
{br}
{control type="tagtree" name="managecats" id="managecats" controller="store" model="storeCategory" draggable=false addable=false menu=true checkable=true values=$record->storeCategory expandonstart=true }

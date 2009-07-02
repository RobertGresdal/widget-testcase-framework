<div class="tabbar">
    <span><a href="view/{$current_path}">Details</a></span>
    <span class="selected">List</span>
</div>

<table>
<tr>
    <th></th>
    <th colspan="3">Action</th>
    <th>Path</th>
</tr>
{if $current_path}
<tr class="odd">
    <td>-</td>
    <td colspan="3">-</td>
    <td><a href="list/"> / (root)</a></td>
</tr>
{/if}
{foreach from=$iterator item=w name=list}
    {php}
        // This code makes a relative path to a testcase
        // based on the base path which was assigned
        $bp = realpath($this->get_template_vars('base_tc_path'));
        $path = realpath($this->get_template_vars('w')->getPathName());
        
        $rp = substr_replace($path,'',0,strlen($bp)+1); // +1 because of the extra '/' at the end
        $this->assign('rel_path', strtr($rp,'\\','/') );
    {/php}
<tr class='{cycle values="even,odd"}'>
    <td>{$smarty.foreach.list.iteration}</td>
    <td><a href="view/{$rel_path}">View</a></td>
    <td><a href="install/{$rel_path}">Install</a></td>
    <td><a href="download/{$rel_path}">Download</a></td>
    <td>{$rel_path|folder_links:'list/'}</td>
</tr>
{/foreach}
</table>
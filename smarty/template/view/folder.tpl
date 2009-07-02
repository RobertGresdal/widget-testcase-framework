{php}
    $mm = new MimeMagic();
{/php} 

<div class="tabbar">
    <span class="selected">Details</span>
    <span><a href="list/{$current_path}">List</a></span>
    {if $tc } 
    <span><a href="install/{$current_path}">Install</a></span>
    <span><a href="download/{$current_path}">Download</a></span>
    {/if}
</div>

{* === DISABLED IF SENTENCE, the old parser is always used. 
       @TODO P4 remove if no errors are popping up later
{if $tc->config->exception}
<div class="warning">
    <span>The new parser threw an exception reading the old format, so
    the old parser were used. If you fix the problematic portion, this 
    warning will go away.</span>
</div>
{/if} *}

{if $warning}
<div class="warning"><span>{$warning}</span></div>
{else}

<div class="widget config">
    <div class="header"><span>Testcase configuration</span></div>
    <div class="comment"><label>Comment</label><span>{$tc->config->comment}</span></div>
    <div class="setting"><label>FileIO</label><span>{$tc->config->fileio|option_boolean}</span></div>
</div>
{/if}

<table class="directory-list">
<tr>
    <th>#</th>
    <!--<th>Full path</th>-->
    <th>Name</th>
    <th>Last modified</th>
    <th>Size</th>
    <!--<th>Type</th>-->
    <th>Mime</th>
    <!--<th>Author</th>-->
    <!--<th>Revision</th>-->
</tr>
<tr>
    <td></td>
    <td colspan="4"><a href="view/{$parentdir}">Parent Directory</a></td>
</tr>
{foreach from=$iterator item=w name=list}
    {php}
        // get the mimetype of this file
        $path = $this->get_template_vars('w')->getPathName();
        $this->assign('mime', $mm->get($path));
        
        $this->assign('path',$path);
    {/php} 
    
    {if $w->getType() == 'dir'}
    <tr class="dir">
        <td>{$smarty.foreach.list.iteration}</td>
        <td>{$w->getPathName()|strip_start:$base_tc_path|folder_links:'view/'}</td>
    </tr>
    {else}
    <tr class="file {cycle values='odd,even'}">
        <td>{$smarty.foreach.list.iteration}</td>
        <!--<td>{$path}</td> -->
        <td>{$w->getPathName()|strip_start:$base_tc_path|folder_links:'view/'}</td>
        <!--<td class="date">{$w->getMTime()|date_format:'%Y-%m-%d %H:%M:%S'}</td>-->
        <td class="date">{$w->getMTime()|date_format:'%d-%b-%Y %H:%M'}</td>
        <td class="number">{$w->getSize()|byte_format}</td>
        <!--<td>{$w->getType()}</td>-->
        <td>{$mime}</td>
    </tr>
    {/if}
{/foreach}
</table>

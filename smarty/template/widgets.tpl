<html>
<head>
<title>Smarty</title>
</head>
<body>

<div>
    <a href="?clearcache">Force redrawing</a>
</div>

<table>
{foreach from=$iterator item=w name=list}
    <tr bgcolor='{cycle values="#eeeeee,#f8f8f8"}'>
    <td>{$smarty.foreach.list.iteration}</td>
    <td>View</td>
    <td>Install</td>
    <td>Download</td>
    <td>{$w->getPathName()}</td>
    </tr>
{/foreach}
</table>

Script complete: {$time}
</body>
</html>
{php}
	$strip = strlen($this->get_template_vars('base_tc_path'))+1;
	$this->assign('strip',$strip);
{/php}
<widget xmlns="http://www.w3.org/ns/widgets">
    <name>{$testcase->path|strip_start:$strip|strtr:'\\':'/'|strtr:'/':'-'}</name>
    <description><![CDATA[{$testcase->config->comment|noBOM}]]></description>
    
    <author>
        <name>{* TODO $testcase->author->name *}</name>
        <organization>{* TODO $testcase->author->organization *}</organization>
        <email/>{* TODO *} 
        <link/>{* TODO *} 
    </author>
    
</widget>

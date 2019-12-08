{* Smarty *}
<!DOCTYPE html>
<html dir="ltr" lang="RU-RU">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="Cache-Control" content="no-cache" />
<title>About </title>
</head>
<body>
<div id="container">
<h2>RPF - Rapid Prototyping Framework</h2>
<p>It is just another small extendable MVC framework for rapid prototyping of APIs, web-services and web-sites.
Supports MySQL\MadiaDb, Firebird, Sqlite databases, Smarty templates, buit-in class autoloader etc... </p>
<p>Framework can be extended by your own classes or components (extensions). Your extension can be placed in a separate subfolder in the folder `Includes`</p>
<p>Yep, it's again the `reinventing of wheel` 😉</p>
<h2>Class Names</h2>
<p>The following describes the mandatory requirements that must be adhered to for autoloader interoperability.</p>
<p>The term “class” refers to classes, interfaces, traits, and other similar structures.</p>
<p>Mandatory:</p>
<ul>
<li>A fully-qualified namespace and class must have the following structure \&lt;Vendor Name&gt;\(&lt;Namespace&gt;\)*&lt;Class Name&gt;
<li>Each namespace must have a top-level namespace ("Vendor Name").
<li>Each namespace can have as many sub-namespaces as it wishes.
<li>Each namespace separator is converted to a DIRECTORY_SEPARATOR when loading from the file system.
<li>Each _ character in the CLASS NAME is converted to a DIRECTORY_SEPARATOR. The _ character has no special meaning in the namespace.
<li>The fully-qualified namespace and class are suffixed with .php when loading from the file system.
<li>Alphabetic characters in vendor names, namespaces, and class names may be of any combination of lower case and upper case.
</ul>
<p>See PSR-0, PSR-4 standards for more info.</p>
<p>&nbsp;</p>
<h2>Typical structure of extension:</h2>
{literal}
<pre>
    |
    +--[ExtensionName]
    |    |
    |    +--[Controller] - Folder for actionControllers MUST have this name;
    |    |    |
    |    |    +--Index.php - Default actionController;
    |    |    +--ActionNameX.php - Other actionControllers (optional);
    |    +--[Model] - Folder for extension's models clases (optional)
    |    |    |
    |    |    +--XXX.php - All files with models classes (optional)
    |    +--[View] - Folder  for extension's viewes classes (optional)
    |    |    |
    |    |    +--YYY.php - - All files with viewes classes (optional)
    |    +--OtherFiles.php - Other classes and functions of your extension (optional).
    |
    +--[OtherExtension] ...
</pre>
{/literal}
<p>An example of the structure of names can be seen in /Includes/Sample/</p>
<p>&nbsp;</p>
<h2>Action Controllers</h2>
<p>Any actionControlles extends abstract class `RPF_Controller_Abstract` with it's properties &amp; methods, or any other ActionController Class. Filename of actionController MUST have have the same name as the action name, or Index.php for default controller. All actionControllers MUST be placed in subfolder `Controller` of your extension's folder (or in folder /Includes/RPF/Controller/ for main framework core).</p>
<p>You can define default action controller in a configuration file (see Config.php)</p>
<p>The following describes the typical code of actionController:</p>
{literal}
<code>
<pre>
/**
* Default actionController for [EctensionName].
* Must be placed in /Includes/ExtensionName/Controller/Index.php
*/
class ExtensionName_Controller_Index extends RPF_Controller_Abstract  
{
    public function __construct()
    {
        parent::__construct();
        
        /**
        Here we can:
         - get input parametres from request and filter it:
        */
        
        $input  = new RPF_Input($this-&gt;request-&gt;getRequestData());
        $someVariable = $input-&gt;filterSingle('someFieldName', RPF_Input::UINT); // unsigned int
        
        /**
        * Supports next types of input variables:
        * - STRING (default '');
        * - NUM, UNUM (default 0);
        * - INT, UINT (default 0);
        * - FLOAT (default 0);
        * - BOOLEAN (default false);
        * - BINARY (default '');
        * - ARRAY_SIMPLE (default array() );
        * - JSON_ARRAY (default array() );
        * - DATETIME (default 0);
        */
        
        /**
         - get some data from model (database or other sourses) and put it into outdata array:
          (model class is placed in /Includes/ExtensionName/Model/SomeObject.php
        */
        
        $model =  new ExtensionName_Model_SomeObject();
        $outdata = $model-&gt;getSomeData();
        
        /**
        * - render the template and send HTML page in response
        * (viewName can be RPF_View_Index or must extends it):
        */
        
        $this->response =  $this-&gt;responseView('viewName', 'templateName', $outdata);
        
        /**
        * - or send it in JSON representation for APIs or web-servises
        * (viewName can be RPF_View_JSON or must extends it):
        */
        
        $this->response =  $this-&gt;responseView('viewName', null, $outdata);
        
        /**
        * - or create image from BLOB and send it
        * (viewName can be RPF_View_Image or must extends it):
        */
        
        $this->response =  $this-&gt;responseView('viewName', null, $imagedata);
        
        /**
        * array $imagedata must contains next elements:
        * - 'image_mime_type' - MIME type of image,
        * - 'image_size' - size of image (bytes),
        * - 'image_data' - binary data from image file or BLOB.
        */
        
        /**
        * - or make redirect to another URL with adittional params in query string:
        */
        
        $this->response =  $this-&gt;responseRedirect($newURL, $outdata);
        
        /**
        * - or send an error:
        */
        
        $this->response =  $this-&lg;responseError('Error message', 404); // or another Http code
        
        /**
         - or stop action and do nothing:
        */
        
        exit();
    }
}
</pre>
</code>
{/literal}
<h2>Routing and URL format.</h2>
<p>Routing supports next URL formats for action controllers:</p>
<ul>
<li>
<p>If Apache moldule `mod_rewrite` (or its analogs for another servers)  is avaialble and RewriteEngine is on:</p>
	<ul>
		<li><p><i>servername/Extension/</i>
		<br>or<br>
		<i>servername/Extension/?param1=value1&amp;param2=value2...</i> for default action controller</p>
		<p>Example: defauul action controller for this sample extension</p>
		&nbsp;<a href="/Sample/" target="_blank">Default action controller w/o parametres in query string</a>
		<br>or<br>
		&nbsp;<a href="/Sample/?region_id=1" target="_blank">Default action controller with parametres in query string.</a>
		</li>
		<li><p><i>servername/Extension/Action</i>
		<br>or<br>
		<i>servername/Extension/Action?param1=value1&amp;param2=value2...</i> for any action controller</p>
		<p>Example: JSON response for API with data from test database `Northwind`</p>
		&nbsp;<a href="/Sample/JSON" target="_blank">JSON action controller w/o parametres in query string</a>
		<br>or<br>
		&nbsp;<a href="/Sample/JSON?region_id=1" target="_blank">JSON action controller with parametres in query string.</a> 
		</li>
		<p><i>Class "RPF_Link" contains built-in link builder. If the "url_rewrite" parameter is set to "1" in the configuration, then the link builder will create this kind of links.</i></p>
	</ul>
</li>
<li>
<p>If Apache moldule `mod_rewrite` (or its analogs for another servers)  is not supported or disabled, or RewriteEngine is off:</p>
	<ul>
		<li><p><i>servername/[index.php]/?package=ExtensionName[/]</i> 
		<br>or<br>
		<i>servername/[index.php]/?package=Extension[/]?param1=value1&amp;param2=value2...</i> for default action controller</p>
		</li>
		<li><p><i>servername/[index.php]/?package=ExtensionName&amp;action=actionName</i> 
		<br>or<br>
		<i>servername/[index.php]/?package=Extension&amp;action=Action&amp;param1=value1&amp;param2=value2...</i> for any action controller</p>
		<p>Example: JSON response for API with data from test database `Northwind`</p>
		&nbsp;<a href="/?package=Sample&action=JSON" target="_blank">Action controller name in query string</a>
		<br>or<br> 
		&nbsp;<a href="/?package=Sample&action=JSON&region_id=1" target="_blank">Action controller name with other parametres in query string.</a> 
		</li>
		<p><i>If the "url_rewrite" parameter is set to "0" or not set in the configuration, then the link builder will create this kind of links.</i></p>
	</ul>
</li>
</ul>
<p>The following describes the .htaccess directives for Apache mod_rewrite:</p>
{literal}
<code>
<pre>
Options -Indexes
DirectoryIndex index.php index.html
&lt;IfModule mod_rewrite.c&gt;
	RewriteEngine On
	RewriteBase /
	RewriteCond %{REQUEST_FILENAME} -f [OR]
	RewriteCond %{REQUEST_FILENAME} -l [OR]
	RewriteCond %{REQUEST_FILENAME} -d
	RewriteRule ^.*$ - [NC,L]
	RewriteRule ^(css/|js/|styles/|images/|favicon\.ico|robots\.txt) - [NC,L]
	RewriteRule ^.*$ index.php [NC,L]
&lt;/IfModule&gt;
</pre>
</code>
{/literal}
<p>The following describes configurtion for Nginx web server:</p>
{literal}
<code>
<pre>
...
location = /css/ {
 }
location = /js/ {
 }
location = /styles/ {
 }
location = /images/ {
 }
location = /favicon.ico {
 }
location = /robots.txt {
 }
location / {
	root /var/ww;
	index index.php index.html;
	try_files $uri $uri/ =404;
	if (!-e $request_filename) {
	    rewrite ^(.+)$ /index.php break;
	}
	#fastCGI params:
        fastcgi_param SCRIPT_FILENAME /var/www/html$fastcgi_script_name;
        fastcgi_param QUERY_STRING    $query_string;
	fastcgi_pass  unix:/var/run/php5-fpm.etk.sock;
	fastcgi_index  index.php;
 }
...
</pre>
</code>
{/literal}
<a name="Db">
<h2>Databases</h2>
<p>Framework supports MySQL\MadiaDb, Firebird, Sqlite databases. You can define database type and othes required parametres in Config file.</p>
<p>You can create your own classes for another databases in /Includes/RPF_Db/ (type of datrabase must be supported by PHP PDO driver).</p>
<p>The following shows an example of working with Sqlite test database ('Northwind'):</p>
<div class="DbSample">
<form name="regions" action="/?package=Sample#Db" method="POST">
{if isset($regions)}
Regions: <select name="region_id">
<option value="0"></option>
{foreach from=$regions key=k item=r}
<option value="{$r.RegionID}" {if isset($region_id) && $region_id eq $r.RegionID}selected{/if} >{$r.RegionID}: {$r.RegionDescription}</option>
{/foreach}
</select>&nbsp;
<button type="Submit">Select territories for this region</button> 
{/if}
{if isset($territories)}
<table border="1" cellpadding="5">
<caption>Territories for {if isset($region_id) && $region_id neq 0}Region {$region_id}{else} all regions:{/if} ({count($territories)} rows)</caption>
<thead>
<tr><th>TerritoryID</th><th>TerritoryDescription</th><th>RegionID</th></tr>
</thead>
<tbody>
{foreach from=$territories key=k item=t}
<tr><td>{$t.TerritoryID}</td><td>{$t.TerritoryDescription}</td><td>{$t.RegionID}</td></tr>
{/foreach}
</tbody>
</table>
{/if}
</form>
</div>
<p>Each action controller can potentially use its own database - database type, address, port, login and password can be overloaded in constructor.  It is not "good practice", but sometimes it can help your application to work with two (or more) different databases or two (or more) different types of databases at same time.</p>
<p>You can find an example of such code in sample extension.</p>
<h2>Query Builder</h2>
<p>You can use built-in query builder for simple SQL queries, when they does not contains table joining or unions.  The following shows an example of code in any methods of classes extends RPF_Model_Abstract:</p>
{literal}
<code>
<pre>
	...
	// create simple query string:
	$query_string = " SELECT { * or list of fields } FROM table_name ";
	// create query buider and set query conditions (array of placeholders creates automatically):
	$qb = new RPF_Model_QueryBuilder(
		$query_string,
		// WHERE, ORDER, LIMIT conditions ( [ ] - not required elements):
		// 1. conditions (not required):
		[ array( 
			[ 'fieldname1' => array( 'condition' =&gt; { '&lt;' , '&gt;' , '=' , '&lt;&gt;', '&lt;=' or '&gt;=' } , 'value' =&gt; $value ), ]
			[ 'fieldname2' => array( 'condition' =&gt; { 'IS' or 'IS NOT' }, 'value' =&gt; null ), ]
			[ 'fieldname3' => array( 'condition' =&gt; 'IN, 'value' =&gt; array( $value_1,  $value_2, .... , $value_N ), ]
			[ 'fieldname4' => array( 'condition' =&gt; 'BETWEEN', 'value' =&gt; array( $value_min,  $value_max ), ]
			...
			[ 'fieldnameN' => array( 'condition' =&gt; ... ]
		), ] 
		// 2. order by (not required):
		[ array( 'fieldname1' =&gt; 'ASC', [ 'fieldname2' =&gt; 'DESC', ... , 'fieldnameN' =&gt; { 'ASC' or 'DESC'} ) ],  ]
		// 3. limit (not required):
		[ array( $first_item, [ $number_of_items ] ) ] 
	);
	// fetch results:
	$results =  $this-&gt;fetch($qb->query, $qb-&gt;placeholders);
	...
</pre>
</code>
{/literal}
<p>Or you can use the classic way:</p>
{literal}
<code>
<pre>
	...
	// create full query string :
	$query_string = " SELECT { * or list of fields } FROM table_name  
		WHERE fieldname1 = :fieldvalue1 AND fieldname2 &lt;&gt; :fieldvalue2  ... AND fieldnameN = :fieldvalueN ";
	// create array of named placeholders:
	$placeholdes = array(
		'fieldvalue1' =&gt; $value1,
		'fieldvalue2' =&gt; $value2,
		...
		'fieldvalueN' =&gt; $valueN
	);
	// fetch results:
	$results =  $this-&gt;fetch($query_string, $placeholdes);
	...
</pre>
</code>
{/literal}
<p>The following functions are available to retrieve data from queries in any classes extends RPF_Model_Abstract: <i>fetch(), fetchRow(), fetchColumn(), fetchValue()</i> (or <i>exec()</i> for insert- or delete- queries).</p>
<h2>Templates</h2>
<p>Current version on framework uses old PHP compiling template engine Smarty (v.3.1). You can use your own favorite template engine, if necessary.  Replace code in the file /Includes/RPF/TemplateEngine.php with your own to use another engine.</p>
<p>&nbsp;</p>
</div>
</body>
</html>
{* Smarty *}
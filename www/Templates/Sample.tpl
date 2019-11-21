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
<p>An example of the structure of names can be seen in /Includes/Sample/</p>
<p>&nbsp;</p>
<h2>Action Controllers</h2>
<p>Any actionControlles extends abstract class `RPF_Controller_Abstract` with it's properties &amp; methods. Filename of actionController MUST have have the same name as the action name, or Index.php for default controller. All actionControllers MUST be placed in subfolder `Controller` of your extension's folder (or in folder /Includes/RPF/Controller/ for main framework core).</p>
<p>You can define default action controller in a configuration file (see Config.php)</p>
<p>The following describes the typical code of actionController:</p>
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
        
        $input  = new RPF_Input($this->request->getRequestData());
        $someVariable = $input->filterSingle('someFieldName', RPF_Input::UINT); // unsigned int
        
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
        $outdata = $model->getSomeData();
        
        /**
        * - render the template and send HTML page in response
        * (viewName can be RPF_View_Index or must extends it):
        */
        
        $this->response =  $this->responseView('viewName', 'templateName', $outdata);
        
        /**
        * - or send it in JSON representation for APIs or web-servises
        * (viewName can be RPF_View_JSON or must extends it):
        */
        
        $this->response =  $this->responseView('viewName', null, $outdata);
        
        /**
        * - or create image from BLOB and send it
        * (viewName can be RPF_View_Image or must extends it):
        */
        
        $this->response =  $this->responseView('viewName', null, $imagedata);
        
        /**
        * array $imagedata must contains next elements:
        * - 'image_mime_type' - MIME type of image,
        * - 'image_size' - size of image (bytes),
        * - 'image_data' - binary data from image file or BLOB.
        */
        
        /**
        * - or make redirect to another URL with adittional params in query string:
        */
        
        $this->response =  $this->responseRedirect($newURL, $outdata);
        
        /**
        * - or send an error:
        */
        
        $this->response =  $this->responseError('Error message', 404); // or another Http code
        
        /**
         - or stop action and do nothing:
        */
        
        exit();
    }
}
</pre>
</code>
<h2>Routing and URL format.</h2>
<p>Routing supports next URL formats for action controllers:</p>
<ul>
<li><p><i>servername/[index.php]/Extension/</i>
<br>or<br>
<i>servername/[index.php]/Extension/?param1=value1&amp;param2=value2...</i> for default controller</p>
<p>Example: defauul action controller for this sample extension</p>
&nbsp;<a href="/index.php/Sample/" target="_blank">Default controller w/o parametres in query string</a>
<br>or<br>
&nbsp;<a href="/index.php/Sample/?region_id=1" target="_blank">Default controller with parametres in query string.</a>
</li>
<li><p><i>servername/[index.php]/Extension/Action</i>
<br>or<br>
<i>servername/[index.php]/Extension/Action?param1=value1&amp;param2=value2...</i> for any controller</p>
<p>Example: JSON response for API with data from test database `Northwind`</p>
&nbsp;<a href="/index.php/Sample/JSON" target="_blank">JSON controller w/o parametres in query string</a>
<br>or<br>
&nbsp;<a href="/index.php/Sample/JSON?region_id=1" target="_blank">JSON controller with parametres in query string.</a> 
</li>
<li><p><i>servername/[index.php]/Extension/?action=actionName</i> 
<br>or<br>
<i>servername/[index.php]/Extension/?action=actionName&amp;param1=value1&amp;param2=value2...</i> for any controller</p>
<p>Example: JSON response for API with data from test database `Northwind`</p>
&nbsp;<a href="/index.php/Sample/?action=JSON" target="_blank">Controller name in query string</a>
<br>or<br> 
&nbsp;<a href="/index.php/Sample/?action=JSON&region_id=1" target="_blank">Controller name with other parametres in query string.</a> 
</li>
</ul>
Note: if you needs to send action name for default controller in query string, you can use `?action=Index` for this action.
<a name="Db">
<h2>Databases</h2>
<p>Framework supports MySQL\MadiaDb, Firebird, Sqlite databases. You can define database type and othes required parametres in Config file.</p>
<p>You can create your own classes for another databases in /Includes/RPF_Db/ (type of datrabase must be supported by PHP PDO driver).</p>
<p>The following shows an example of working with Sqlite test database ('Northwind'):</p>
<div class="DbSample">
<form name="regions" action="/index.php/Sample/#Db" method="POST">
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
<h2>Templates</h2>
<p>Current version on framework uses old PHP compiling template engine Smarty (v.3.1). You can use your own favorite template engine, if necessary.  Replace code in the file /Includes/RPF/TemplateEngine.php with your own to use another engine.</p>
<p>&nbsp;</p>
</div>
</body>
</html>
{* Smarty *}
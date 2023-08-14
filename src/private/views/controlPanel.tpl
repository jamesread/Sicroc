<div class = "controlPanel">
{if \libAllure\Session::hasPriv('USERGROUPS')}
<h2>Usergroups</h2>
<a href = "?pageIdent=USERGROUP_CREATE">Create</a>
<a href = "?pageIdent=USERGROUP_ASSIGN">Assign User To Group</a>
{/if}

{if \libAllure\Session::hasPriv('PAGE_STRUCTURE')}
<h2>Page Structure</h2>
<a href = "?pageIdent=NAVIGATION_LIST">Navigation</a>
<a href = "?pageIdent=NAVIGATION_CREATE">Create</a>
<a href = "?pageIdent=PAGE_LIST">Pages</a>
<a href = "?pageIdent=WIDGET_LIST">Widgets</a>
<a href = "?pageIdent=WIDGET_REGISTER">Widget Register</a>
<a href = "?pageIdent=WIDGET_CREATE">Widget Instanciate</a>
{/if}

{if \libAllure\Session::hasPriv('DATASOURCES')}
<h2>Datasources</h2>
<a href = "?pageIdent=TABLE_CONFIGURATION_LIST">Table Configurations</a>
{/if}

{if \libAllure\Session::hasPriv('ADMIN')}
<h2>Setup</h2>
<a href = "setup.php">Rerun Setup</a>
{/if}
</div>

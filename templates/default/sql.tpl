{if $erreur}
    <div id="errorbox">
        <h1>{_T string="- ERROR -"}</h1>
        <ul>
            {foreach from=$liste_erreurs item=err}
                <li>{$err}</li>
                {/foreach}
        </ul>
    </div>
{/if}
<div class="bigtable">
    <table class="listing">
        <caption class="ui-state-active ui-corner-top">{_T string="SQL.EXISTENCE TABLES"}</caption>
        <tr>
            <th>Table</th>
            <th>Existe ?</th>
            <th>Version</th>
            <th>Cols</th>
        </tr>
        {foreach from=$liste_tables item=table key=clef}
            <tr class="{if $table@index is even}even{else}odd{/if}">
                <td width="50%">
                    <b> 
                        {$clef}
                    </b>
                </td>
                <td width="40%">
                    <b>
                        {if $table->existe}
                            <font color="green">
                            {_T string="SQL.EXISTE"}
                            </font>
                        </b> / {$table->nb_lignes} {_T string="SQL.ENREGISTREMENTS"}
                    {else}
                        <font color="red">
                        {_T string="SQL.EXISTE PAS"}
                        </font>
                        </b>
                    {/if}
                </td>
                <td width="5%" style="text-align: center !important">
                    {$table->version}
                </td>
                <td width="5%" style="text-align: center !important">
                    {if $table->existe}
                        <a href="?table={$table->nom_table}">
                            <img src="picts/props.png">
                        </a>
                    {/if}
                </td>
            </tr>
        {/foreach}
    </table>
</div>
<p></p>
{if $montre_infos_table}
    <table class="listing" style="margin-left: auto; margin-right: auto; width: 65% !important">
        <caption class="ui-state-active ui-corner-top">{_T string="SQL.DESCRIPTION TABLE"} {$nom_table}</caption>
        <thead>
            <tr>
                <th>#</th>
                <th>Colonne</th>
                <th>Type</th>
                <th>Nullable</th>
                <th>PK</th>
                <th>Incr.</th>
                <th>Index</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$infos_table item=infos_colonne}
                <tr class="{if $infos_colonne@index is even}even{else}odd{/if}">
                    <td>
                        {$infos_colonne->numero}
                    </td>
                    <td>
                        {$infos_colonne->name}
                    </td>
                    <td>
                        {$infos_colonne->data_type}
                        {if $infos_colonne->length neq ''}
                            ({$infos_colonne->length})
                        {/if}
                    </td>
                    <td align="center">
                        {if $infos_colonne->nullable eq 1}
                            <img src="picts/check.png">
                        {/if}
                    </td>
                    <td align="center">
                        {if $infos_colonne->primary eq 1}
                            <img src="picts/check.png">
                        {/if}
                    </td>
                    <td align="center">
                        {if $infos_colonne->identity eq 1}
                            <img src="picts/check.png">
                        {/if}
                    </td>
                    <td align="center">
                        {if $infos_colonne->index eq 1}
                            <img src="picts/check.png">
                        {/if}
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
    <p></p>
{/if}

<script>
    function jouerScript(nomScript) {
        if (confirm('{_T string="SQL.SUR LANCER SCRIPT"}' + nomScript)) {
            document.getElementById('nom_script').value = nomScript;
            document.getElementById('formSQL').submit();
        } else {
            return false;
        }
    }
</script>

<form action="sql.php" method="post" id="formSQL">
    <input type="hidden" id="nom_script" name="nom_script" value="">
    <table class="listing" style="margin-left: auto; margin-right: auto; width: 80% !important">
        <caption class="ui-state-active ui-corner-top">{_T string="SQL.JOUER SCRIPT"}</caption>
        <thead>
            <tr>
                <th>{_T string="SQL.NOM SCRIPT"}</th>
                <th>{_T string="SQL.DATE MODIF"}</th>
                <th>{_T string="SQL.NB EXEC"}</th>
                <th>{_T string="SQL.DERNIERE EXEC"}</th>
                <th>{_T string="SQL.ACTION"}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$liste_scripts item=script}
                <tr class="{if $script@index is even}even{else}odd{/if}">
                    <td>
                        {$script->filename}
                    </td>
                    <td>
                        {$script->modifie}
                    </td>
                    <td align="center">
                        {$script->nb_execution}
                    </td>
                    <td>
                        {$script->derniere_execution}
                    </td>
                    <td align="center">
                        <div class="button-container">
                            <input type="submit" id="script_sql" onClick="jouerScript('{$script->filename}')" value="{_T string="SQL.LANCER"}">
                        </div>
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
</form>

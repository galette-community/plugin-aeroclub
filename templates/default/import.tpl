{if $table_created}
    <div id="infobox">
        <h1>
            {_T string="IMPORT.TABLE SQL CREEE"}
        </h1>
    </div>
{/if}
{if $file_imported}
    <div id="infobox">
        <h1>
            {_T string="IMPORT.REUSSI"} ({$lignes_lues} {_T string="IMPORT.LIGNES LUES"}, {$lignes_mises_a_jour} {_T string="IMPORT.LIGNES IMPORTEES"})
        </h1>
    </div>
{/if}
{if $file_deleted}
    <div id="infobox">
        <h1>
            {$files_deleted} {_T string="IMPORT.FICHIERS SUPPRIMES"}
        </h1>
    </div>
{/if}
<form action="import.php" enctype="multipart/form-data" method="post">
<div class="bigtable">
    <fieldset class="cssform">
        <legend class="ui-state-active ui-corner-top">{_T string="IMPORT.TITRE FICHIER"}</legend>
        <div>
{if !$fichier_deja_importe} 
            <p>
                <span class="bline">
                    {_T string="IMPORT.CHOIX FICHIER"}
                </span>
                <input type="file" name="pilote_import_file" size="40">
            </p>
            <p>
                <span class="bline">
                    <label for="filter">
                        {_T string="IMPORT.FILTRER IMPORT"}
                    </label>
                </span>
                <input type="checkbox" name="filter" id="filter" checked="checked" value="vrai">
                <br/>{_T string="IMPORT.DATE DERNIER IMPORT"} {$date_dernier_import}
                <br/>{_T string="IMPORT.DATE IMPORT COMPLEMENT"}
            </p>
            <p>
                <span class="bline">
                    <label for="ignore_section">
                        {_T string="IMPORT.IGNORE SECTION"} <i>«&nbsp;{$nom_section}&nbsp;»</i> :
                    </label>
                </span>
                <input type="checkbox" name="ignore_section" id="ignore_section" value="vrai">
            </p>    
{/if}
{if $choix_annee}
            <p>
            <span class="bline">{_T string="IMPORT.CHOIX ANNEE IMPORT"}</span>
            <select name="import_annee">
                {foreach from=$liste_annees item=an}
                <option value="{$an}"{if $annee_selectionnee eq $an} selected="selected"{/if}>{$an}</option>
                {/foreach}
            </select>
            <span class="exemple">{_T string="IMPORT.DONNEES ANNEES SUPPRIMEES"}</span>
            <input type="hidden" name="name_uploaded_file" value="{$name_uploaded_file}">
            </p>
{/if}
        </div>
    </fieldset>
</div>
<div class="button-container">
    <input type="submit" id="import" name="import" value="{_T string="IMPORT.ENVOYER"}">
</div>

<h3>{_T string="HISTO IMPORT.PAGE TITLE"}</h3>
<table class="listing">
    <thead>
        <tr>
            <th></th>
            <th>{_T string="IMPORT.NOM"}</th>
            <th>{_T string="IMPORT.TAILLE"}</th>
            <th>{_T string="IMPORT.NB ADH"}</th>
            <th>{_T string="IMPORT.NB OPE"}</th>
            <th>{_T string="IMPORT.LIGNES TRAITEES"}</th>
            <th>{_T string="IMPORT.LIGNES TOTAL"}</th>
            <th>{_T string="IMPORT.IMPORTE LE"}</th>
            <th>{_T string="IMPORT.EXPORTE LE"}</th>
            <th>{_T string="IMPORT.TYPE"}</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$historique item=histo name=hist}
            <tr>
                <td class="tbl_line_{if $smarty.foreach.hist.index is odd}even{else}odd{/if}">
                    <input type="checkbox" class="cb_pilote" name="delete_files[]" value="{$histo->name}">
                </td>
                <td class="tbl_line_{if $smarty.foreach.hist.index is odd}even{else}odd{/if}">
                    <a href="historique_import/{$histo->name}" target="_blank">
                        {$histo->name}
                    </a>
                </td>
                <td class="tbl_line_{if $smarty.foreach.hist.index is odd}even{else}odd{/if}" align="right">
                    {$histo->size}
                </td>
                <td class="tbl_line_{if $smarty.foreach.hist.index is odd}even{else}odd{/if}" align="right">
                    {$histo->members}
                </td>
                <td class="tbl_line_{if $smarty.foreach.hist.index is odd}even{else}odd{/if}" align="right">
                    {$histo->operations}
                </td>
                <td class="tbl_line_{if $smarty.foreach.hist.index is odd}even{else}odd{/if}" align="right">
                    {$histo->imported}
                </td>
                <td class="tbl_line_{if $smarty.foreach.hist.index is odd}even{else}odd{/if}" align="right">
                    {$histo->lines}
                </td>
                <td class="tbl_line_{if $smarty.foreach.hist.index is odd}even{else}odd{/if}">
                    {$histo->date}
                </td>
                <td class="tbl_line_{if $smarty.foreach.hist.index is odd}even{else}odd{/if}">
                    {$histo->datemaj}
                </td>
                <td class="tbl_line_{if $smarty.foreach.hist.index is odd}even{else}odd{/if}">
                    {$histo->type}
                </td>
            </tr>
        {/foreach}
    </tbody>
</table>
<br/>
<a id="checkAll">
    {_T string="IMPORT.CHECK"}
</a>
    -
<a id="uncheckAll">
    {_T string="IMPORT.UNCHECK"}
</a>
    -
<a id="invertAll">
    {_T string="IMPORT.INVERT"}
</a>
<br/>
<div class="button-container">
    <input type="submit" id="supprimer" name="supprimer" value="{_T string="IMPORT.SUPPRIMER"}">
</div>
</form>
<script>
    $('#checkAll').click(function () {
        $('.cb_pilote').attr('checked', 'checked');
    });

    $('#uncheckAll').click(function () {
        $('.cb_pilote').removeAttr('checked');
    });

    $('#invertAll').click(function () {
        $('.cb_pilote').each(function(){
            if ($(this).is(':checked')) {
                $(this).removeAttr('checked');
            } else {
                $(this).attr('checked', 'checked');
            }
        });
    });
</script>

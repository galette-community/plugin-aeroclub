<form action="types_vols_pilotes.php" method="get">
    <p>Choix de l'année :
        {foreach from=$annees item=an}
            <input type="radio" name="annee" value="{$an}" id="sel_annee_{$an}"{if $an eq $sel_annee} checked="checked"{/if}>
            <label for="sel_annee_{$an}">{$an}</label>
        {/foreach}
    </p>
    <p>Choix des types de vols :
        {foreach from=$types_vols item=vol key=type}
            <input type="checkbox" name="selection_type_vols[]" id="tvol_{$vol}" value="{$type}"{if $selection->$type} checked="checked"{/if}>
            <label for="tvol_{$vol}">{$vol}</label>
        {/foreach}
    </p>
    <p>Tri :
        <select name="tri">
            <option value="nom_adh"{if $tri eq 'nom_adh'} selected="selected"{/if}>{_T string="HEURES.ADHERENT NOM"}</option>
            <option value="prenom_adh"{if $tri eq 'prenom_adh'} selected="selected"{/if}>{_T string="HEURES.ADHERENT PRENOM"}</option>
            <option value="login_adh"{if $tri eq 'login_adh'} selected="selected"{/if}>{_T string="HEURES.ADHERENT PSEUDO"}</option>
            {foreach from=$types_vols item=vol key=type}
            <option value="{$type}"{if $tri eq $type} selected="selected"{/if}>{$vol}</option>
            {/foreach}            
        </select>
        <input type="radio" name="direction" value="asc" id="dir_asc"{if $direction eq 'asc'} checked="checked"{/if}>
        <label for="dir_asc">A à Z</label>
        <input type="radio" name="direction" value="desc" id="dir_desc"{if $direction eq 'desc'} checked="checked"{/if}>
        <label for="dir_desc">Z à A</label>
    </p>
    <p align="center">
        <input type="submit" id="zoom" value="Afficher">
    </p>
    <table class="listing">
        <thead>
            <tr>
                <th class="listing"><a href="?annee={$sel_annee}&tri=nom_adh&direction={if $tri eq 'nom_adh' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="HEURES.ADHERENT NOM"}</a>{if $tri eq 'nom_adh' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'nom_adh' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                <th class="listing"><a href="?annee={$sel_annee}&tri=prenom_adh&direction={if $tri eq 'prenom_adh' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="HEURES.ADHERENT PRENOM"}</a>{if $tri eq 'prenom_adh' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'prenom_adh' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                <th class="listing"><a href="?annee={$sel_annee}&tri=login_adh&direction={if $tri eq 'login_adh' && $direction eq 'asc'}desc{else}asc{/if}">{_T string="HEURES.ADHERENT PSEUDO"}</a>{if $tri eq 'login_adh' && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq 'login_adh' && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                    {foreach from=$types_vols item=vol key=type}
                    {if $selection->$type}
                    <th class="listing"><a href="?annee={$sel_annee}&tri={$type}&direction={if $tri eq $type && $direction eq 'asc'}desc{else}asc{/if}">{$vol}</a>{if $tri eq $type && $direction eq 'asc'} <img src="{$template_subdir}images/down.png">{elseif $tri eq $type && $direction eq 'desc'} <img src="{$template_subdir}images/up.png">{/if}</th>
                    {/if}
                    {/foreach}
            </tr>
        </thead>
        <tbody>
            {foreach from=$stats item=adh name=adherent}
                <tr>
                    <td class="tbl_line_{if $smarty.foreach.adherent.index is even}even{else}odd{/if}">{$adh->nom_adh}</td>
                    <td class="tbl_line_{if $smarty.foreach.adherent.index is even}even{else}odd{/if}">{$adh->prenom_adh}</td>
                    <td class="tbl_line_{if $smarty.foreach.adherent.index is even}even{else}odd{/if}">{$adh->login_adh}</td>
                    {foreach from=$types_vols item=vol key=type}
                    {if $selection->$type}
                        <td class="tbl_line_{if $smarty.foreach.adherent.index is even}even{else}odd{/if}" align="right">{$adh->$type}</td>
                    {/if}
                    {/foreach}
                </tr>
            {/foreach}
        </tbody>
    </table>
</form>
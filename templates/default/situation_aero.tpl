{if $login->isAdmin()}
<form action="situation_aero.php" method="get">
<div class="bigtable">
    <fieldset class="cssform">
        <legend class="ui-state-active ui-corner-top">{_T string="SITUATION AERO.TABLE TITLE"}</legend>
        <div>
            <p>
                <span class="bline">{_T string="SITUATION AERO.ADHERENT"}</span>
                <select name="login_adherent">
                {foreach from=$liste_adherents key=pseudo item=adh}
                    <option value="{$pseudo}"{if $adherent_selectionne eq $pseudo} selected="selected"{/if}>{$adh}</option>
                {/foreach}
                </select>
            </p>
        </div>
    </fieldset>
</div>
<div class="button-container">
    <input type="submit" id="calendrier" value="{_T string="SITUATION AERO.AFFICHER"}">
</div>
<p></p>
</form>
{/if}
<div class="bigtable">
    <table class="details">
        <caption class="ui-state-active ui-corner-top">{_T string="SITUATION AERO.SITUATION"}</caption>
        <tr>
            <th>{_T string="SITUATION AERO.SOLDE"}</th>
            <td><b>{if $solde lt 0}<font color="red">{else}<font color="green">{/if}{$solde_format}</font></b>
                <span style="background-color:red; font-weight:bold">{$solde_debiteur}</span></td>
        </tr>
        <tr>
            <th>{_T string="SITUATION AERO.TEMPS VOL"}</th>
            <td>{$temps_vol}</td>
        </tr>
        <tr>
            <th>{_T string="SITUATION AERO.TEMPS VOL GLISSANT"}</th>
            <td>{$temps_vol_glissant}</td>
        </tr>
        <tr>
            <th>{_T string="SITUATION AERO.VALIDITE LICENSE"}</th>
            <td>{$date_license}</td>
        </tr>
        <tr>
            <th>{_T string="SITUATION AERO.VISITE MEDICALE"}</th>
            <td>{$visite_medicale}</td>
        </tr>
        <tr>
            <th>{_T string="SITUATION AERO.VOL CONTROLE"}</th>
            <td>{$vol_controle}</td>
        </tr>
        <tr>
            <th>{_T string="SITUATION AERO.NB ATTERISSAGES"}</th>
            <td>{$nb_atterissages}</td>
        </tr>
        <tr>
            <th>{_T string="SITUATION AERO.DERNIER VOL"}</th>
            <td>{$dernier_vol}</td>
        </tr>
    </table>
</div>
<div class="bigtable">
    <table class="details">
        <caption class="ui-state-active ui-corner-top">{_T string="SITUATION AERO.WARNING"}</caption>
    </table>
</div>
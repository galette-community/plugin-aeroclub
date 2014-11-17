{if $login->isAdmin()}
<form action="fiche_pilote.php" method="get">
<div class="bigtable">
    <fieldset class="cssform">
        <legend class="ui-state-active ui-corner-top">{_T string="FICHE PILOTE.TABLE TITLE"}</legend>
        <div>
            <p>
                <span class="bline">{_T string="FICHE PILOTE.ADHERENT"}</span>
                <select name="login_adherent">
                {foreach from=$liste_adherents item=adh}
                    <option value="{$adh->pseudo_adh}"{if $adherent_selectionne eq $adh->pseudo_adh} selected="selected"{/if}>{$adh->nom_adh} {$adh->prenom_adh} ({$adh->pseudo_adh})</option>
                {/foreach}
                </select>
            </p>
        </div>
    </fieldset>
</div>
<div class="button-container">
    <input type="submit" id="calendrier" value="{_T string="FICHE PILOTE.AFFICHER"}">
</div>
<p></p>
</form>
{/if}
<div class="bigtable">
    <table class="details">
        <caption class="ui-state-active ui-corner-top">{_T string="FICHE PILOTE.FICHE"}</caption>
        <tr>
            <th>{_T string="FICHE PILOTE.CODE"}</th>
            <td>{$adherent->nickname}</td>
        </tr>
        <tr>
            <th>{_T string="FICHE PILOTE.NOM"}</th>
            <td>{$adherent->name}</td>
        </tr>
        <tr>
            <th>{_T string="FICHE PILOTE.PRENOM"}</th>
            <td>{$adherent->surname}</td>
        </tr>
        <tr>
            <th>{_T string="FICHE PILOTE.DATE NAISSANCE"}</th>
            <td>{$adherent->birthdate}</td>
        </tr>
        <tr>
            <th>{_T string="FICHE PILOTE.ADRESSE"}</th>
            <td>{$adherent->adress}</td>
        </tr>
        <tr>
            <th>{_T string="FICHE PILOTE.CP"}</th>
            <td>{$adherent->zipcode}</td>
        </tr>
        <tr>
            <th>{_T string="FICHE PILOTE.VILLE"}</th>
            <td>{$adherent->town}</td>
        </tr>
        <tr>
            <th>{_T string="FICHE PILOTE.EMAIL"}</th>
            <td><a href="mailto:{$adherent->email}">{$adherent->email}</a></td>
        </tr>
    </table>
    <table class="details">
        <caption class="ui-state-active ui-corner-top">{_T string="FICHE PILOTE.TELEPHONES"}</caption>
        <tr>
            <th>{_T string="FICHE PILOTE.TELEPHONE"}</th>
            <td>{$adherent->phone}
        </tr>
        <tr>
            <th>{_T string="FICHE PILOTE.TEL TRAVAIL"}</th>
            <td>{$complement->tel_travail}
        </tr>
        <tr>
            <th>{_T string="FICHE PILOTE.PORTABLE"}</th>
            <td>{$adherent->gsm}
        </tr>
    </table>
    <table class="details">
        <caption class="ui-state-active ui-corner-top">{_T string="FICHE PILOTE.SITUATION AERO"}</caption>
        <tr>
            <th>{_T string="FICHE PILOTE.VISITE MEDICALE"}</th>
            <td>{$complement->date_visite_medicale}
        </tr>
        <tr>
            <th>{_T string="FICHE PILOTE.VOL CONTROLE"}</th>
            <td>{$complement->date_vol_controle}
        </tr>
        <tr>
            <th>{_T string="FICHE PILOTE.DERNIER VOL"}</th>
            <td>{$dernier_vol}
        </tr>
        <tr>
            <th>{_T string="FICHE PILOTE.FIN LICENSE"}</th>
            <td>{$complement->date_fin_license}
        </tr>
        <tr>
            <th>{_T string="FICHE PILOTE.NO BB"}</th>
            <td>{$complement->no_bb}
        </tr>
        <tr>
            <th>{_T string="FICHE PILOTE.NO PPL"}</th>
            <td>{$complement->no_ppl}
        </tr>
        <tr>
            <th>{_T string="FICHE PILOTE.AUTRE QUALIF"}</th>
            <td>{$adherent->others_infos}
        </tr>
        <tr>
            <th>{_T string="FICHE PILOTE.ELEVE"}</th>
            <td>{if $complement->est_eleve}Oui{else}Non{/if}
        </tr>
    </table>
</div>
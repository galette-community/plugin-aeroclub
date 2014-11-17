{if $enregistre}
    <div id="infobox">
        <h1>
            {_T string="NEW OPERATION.ENREGISTRE OK"}
        </h1>
    </div>
{/if}
{if $pas_enregistre}
    <div id="warningbox">
        <h1>
            {_T string="NEW OPERATION.PAS ENREGISTRE"}
        </h1>
    </div>
{/if}

<form action="new_operation.php" method="post">
<input type="hidden" name="operation_id" value="{$operation->operation_id}">
<input type="hidden" name="origine" value="{$origine}">
<div class="bigtable">
    <fieldset class="cssform">
        <legend class="ui-state-active ui-corner-top">{_T string="NEW OPERATION.TITRE"}</legend>
        <div>
            <p>
                <span class="bline">{_T string="NEW OPERATION.ADHERENT"}</span>
                <select name="adherent_id" required>
                    {foreach from=$liste_adherents item=adherent}
                        <option value="{$adherent->id_adh}"{if $operation->id_adherent eq $adherent->id_adh} selected="selected"{/if}>{$adherent->nom_adh} {$adherent->prenom_adh} ({$adherent->pseudo_adh})</option>
                    {/foreach}
                </select>
            </p>
            <p>
                <span class="bline">{_T string="NEW OPERATION.TYPE OPERATON"}</span>
                <select name="sel_type_operation" id="adherent_id">
                    {foreach from=$liste_type_operations item=type_oper}
                    <option value="{$type_oper}"{if $type_oper eq $operation->type_operation} selected="selected"{/if}>{$type_oper}</option>
                    {/foreach}
                    <option value="-null-">{_T string="NEW OPERATION.AUTRE"}</option>
                </select>
                {_T string="NEW OPERATION.OU AUTRE"}
                <input type="text" name="autre_type_operation">
            </p>
            <p>
                <span class="bline">{_T string="NEW OPERATION.LIBELLE OPERATION"}</span>
                <input type="text" size="80" name="libelle_operation" value="{$operation->libelle_operation}" required>
            </p>
            <p>
                <span class="bline">{_T string="NEW OPERATION.EXERCICE"}</span>
                <input type="text" size="4" name="exercice" value="{$operation->access_exercice}" required>
            </p>
            <p>
                <span class="bline">{_T string="NEW OPERATION.DATE"}</span>
                <input type="text" size="10" name="date_operation" id="date_operation" value="{$operation->date_operation_IHM}" required>
            </p>
            <p>
                <span class="bline">{_T string="NEW OPERATION.MONTANT"}</span>
                <input type="text" size="8" name="montant" value="{$operation->montant_operation}" required> EUR
            </p>
        </div>
    </fieldset>
    <fieldset class="cssform">
        <legend class="ui-state-active ui-corner-top">{_T string="NEW OPERATION.VOL"}</legend>
        <div>
            <p>
                <span class="bline">{_T string="NEW OPERATION.AVION"}</span>
                <select name="immatriculation">
                {foreach from=$liste_avions item=avion}
                    <option value="{$avion->immatriculation}"{if $operation->immatriculation eq $avion->immatriculation} selected="selected"{/if}>{$avion->immatriculation}</option>
                {/foreach}
                </select>
            </p>
            <p>
                <span class="bline">{_T string="NEW OPERATION.TYPE VOL"}</span>
                <select name="sel_type_vol">
                {foreach from=$liste_type_vols item=type_vol}
                    <option value="{$type_vol}"{if $operation->type_vol eq $type_vol} selected="selected"{/if}>{$type_vol}</option>
                {/foreach}
                    <option value="-null-">{_T string="NEW OPERATION.AUTRE"}</option>
                </select>
                {_T string="NEW OPERATION.OU AUTRE"}
                <input type="text" name="autre_type_vol">
            </p>
            <p>
                <span class="bline">{_T string="NEW OPERTATION.AEROPORT"}</span>
                {_T string="NEW OPERTATION.DEPART"}
                <input type="text" size="4" name="depart" value="{$operation->aeroport_depart}"> /
                {_T string="NEW OPERTATION.ARRIVEE"}
                <input type="text" size="4" name="arrivee" value="{$operation->aeroport_arrivee}">
            </p>
            <p>
                <span class="bline">{_T string="NEW OPERATION.PASSAGERS"}</span>
                <input type="text" size="1" name="nb_passagers" value="{$operation->nb_passagers}">
            </p>
            <p>
                <span class="bline">{_T string="NEW OPERATION.INSTRUCTEUR"}</span>
                <select name="instructeur">
                    <option value="*">*</option>
                {foreach from=$liste_instructeurs item=instructeur}
                    <option value="{$instructeur->code}"{if $operation->instructeur eq $instructeur->code} selected="selected"{/if}>{$instructeur->nom} ({$instructeur->code})</option>
                {/foreach}
                </select>
            </p>
            <p>
                <span class="bline">{_T string="NEW OPERATION.ATTERISSAGE"}</span>
                <input type="text" size="1" name="atterissage" value="{$operation->nb_atterrissages}">
            </p>
            <p>
                <span class="bline">{_T string="NEW OPERATION.DUREE"}</span>
                <input type="text" size="1" name="heures" value="{$operation->duree_heure}"> h
                <input type="text" size="2" name="minutes" value="{$operation->duree_minute}"> min
            </p>
        </div>
    </fieldset>
</div>
<div class="button-container">
    <input type="submit" id="sauver" name="sauver" value="{_T string="NEW OPERATION.ENREGISTRER"}">
    <input type="submit" id="annuler" name="annuler" value="{_T string="NEW OPERATION.ANNULER"}" onclick="document.location = '{if $origine eq 'compte_vol'}compte_vol{else}liste_vols{/if}.php?msg=annule&nb_lignes=20&login_adherent={$login_adherent}'; return false;">
</div>
</form>
<script type="text/javascript">
    $(function() {
        $('#date_operation').datepicker({
            changeMonth: true,
            changeYear: true,
            showOn: 'both',
            buttonImage: '{$template_subdir}images/calendar.png',
            buttonImageOnly: true,
            maxDate: '-0d',
            yearRange: 'c-5'
        });
    });
</script>

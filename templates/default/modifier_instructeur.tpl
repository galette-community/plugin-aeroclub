<form action="modifier_instructeur.php" method="post">
<input type="hidden" name="instructeur_id" value="{$instructeur->instructeur_id}">
<div class="bigtable">
    <fieldset class="cssform">
        <legend class="ui-state-active ui-corner-top">{_T string="MODIFIER INSTRUCTEUR.TITRE"}</legend>
        <div>
            <p>
            <span class="bline">{_T string="MODIFIER INSTRUCTEUR.CODE"}</span>
            <input type="text" name="code" value="{$instructeur->code}" required>
            </p>
            <p>
            <span class="bline">{_T string="MODIFIER INSTRUCTEUR.ADHERENT"}</span>
            <select name="adherent_id" id="adherent_id" onChange="changerValeurs()">
                <option value="null">--- Externe ---</option>
                {foreach from=$liste_adherents key=id_adh item=adherent}
                <option value="{$id_adh}"{if $instructeur->adherent_id eq $id_adh} selected{/if}>{$adherent}</option>
                {/foreach}
            </select>
            </p>
            <p>
            <span class="bline">{_T string="MODIFIER INSTRUCTEUR.NOM"}</span>
            <input type="text" name="nom" id="nom" value="{$instructeur->nom}" required>
            </p>
            <p>
            <span class="bline">{_T string="MODIFIER INSTRUCTEUR.CODE ADHERENT"}</span>
            <input type="text" name="code_adherent" id="code_adherent" value="{$instructeur->code_adherent}">
            </p>
        </div>
    </fieldset>
</div>
<div class="button-container">
    <input type="submit" id="sauver" name="modifier" value="{_T string="MODIFIER INSTRUCTEUR.ENREGISTRER"}">
    <input type="submit" id="annuler" name="annuler" value="{_T string="MODIFIER INSTRUCTEUR.ANNULER"}" onclick="document.location = 'liste_instructeurs.php?msg=pas_enregistre'">
</div>
</form>
<script>
function changerValeurs() {ldelim}
    var adherent = document.getElementById('adherent_id').options[document.getElementById('adherent_id').selectedIndex].text;
    if(adherent.indexOf('(') != -1) {ldelim}
        var ind = adherent.indexOf('(');
        document.getElementById('nom').value = adherent.substring(0, ind - 1);
        document.getElementById('code_adherent').value = adherent.substring(ind + 1, adherent.length - 1);
    {rdelim} else {ldelim}
        document.getElementById('nom').value = '';
        document.getElementById('code_adherent').value = '';
    {rdelim}
{rdelim}
</script>
{if $mot_passe_change}
    <div id="infobox">{_T string="CHANGE PWD.MOT DE PASSE CHANGE"}</div>
{/if}
{if $erreurs}
    <div id="errorbox">
        <h1>{_T string="- ERROR -"}</h1>
        <ul>
        {foreach from=$liste_erreurs item=err}
            <li>{$err}</li>
        {/foreach}
        </ul>
    </div>
{/if}
<form action="change_pwd.php" method="post">
<div class="bigtable">
    <fieldset class="cssform">
        <legend class="ui-state-active ui-corner-top">{_T string="CHANGE PWD.CHOIX MEMBRE"}</legend>
        <div>
            <p>
            <span class="bline">{_T string="CHANGE PWD.LISTE MEMBRE"}</span>
            <select name="adherent_id">
            {foreach from=$liste_adherents item=adherent key=id}
                <option value="{$id}"{if $id eq $select_adherent_id} selected="selected"{/if}>{$adherent}</option>
            {/foreach}
            </select>
            </p>
            <p>
            <span class="bline">{_T string="CHANGE PWD.MOT DE PASSE"}</span>
            <input type="password" name="mot_de_passe" required>
            <span class="exemple">{_T string="CHANGE PWD.COMPLEXITE"}</span>
            </p>
            <p>
            <span class="bline">{_T string="CHANGE PWD.CONFIRM MOT DE PASSE"}</span>
            <input type="password" name="confirm_mot_de_passe" required>
            <span class="exemple">{_T string="CHANGE PWD.CONFIRMER"}</span>
            </p>
        </div>
    </fieldset>
</div>
<div class="button-container">
    <input type="submit" id="mot_de_passe" value="{_T string="CHANGE PWD.MODIFIER"}">
</div>
</form>


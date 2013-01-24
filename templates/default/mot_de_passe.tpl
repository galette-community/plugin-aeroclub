{if $mot_passe_change}
    <div id="infobox">{_T string="MOT DE PASSE.MOT DE PASSE CHANGE"}</div>
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
<form action="mot_de_passe.php" method="post">
<div class="bigtable wrmenu">
    <table class="details">
        <caption class="ui-state-active ui-corner-top">{_T string="MOT DE PASSE.CHANGER MOT DE PASSE"}</caption>
        <tr>
            <th>{_T string="MOT DE PASSE.ANCIEN"}</th>
            <td><input type="password" name="ancien_passe" required>
            <span class="exemple">{_T string="MOT DE PASSE.CONFIRM ANCIEN"}</td>
        </tr>
        <tr>
            <th>{_T string="MOT DE PASSE.MOT DE PASSE"}</th>
            <td><input type="password" name="mot_de_passe" required>
            <span class="exemple">{_T string="MOT DE PASSE.COMPLEXITE"}</td>
        </tr>
        <tr>
            <th>{_T string="MOT DE PASSE.CONFIRM MOT DE PASSE"}</th>
            <td><input type="password" name="confirm_mot_de_passe" required>
            <span class="exemple">{_T string="MOT DE PASSE.CONFIRMER"}</td>
        </tr>
    </table>
</div>
<div class="button-container">
    <input type="submit" id="mot_de_passe" value="{_T string="MOT DE PASSE.MODIFIER"}">
</div>
</form>


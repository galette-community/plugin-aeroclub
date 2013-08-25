{if $parametres_sauves}
    <div id="infobox">{_T string="PARAMETRES.PARAMETRES SAUVES"}</div>
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
<form action="parametres.php" method="post" class="tabbed">
<div id="prefs_tabs">
    <ul>
        <li><a href="#general">{_T string="PARAMETRES.TITRE WEB"}</a></li>
        <li><a href="#parameters">{_T string="PARAMETRES.TITRE ACCESS"}</a></li>
    </ul>
    <fieldset id="general" class="cssform">
        <legend>{_T string="PARAMETRES.LEGENDE WEB"}</legend>
        <table class="listing">
            <caption class="ui-state-active ui-corner-top">{_T string="PARAMETRES.LISTE"}</caption>
            <thead>
                <tr>
                    <th>{_T string="PARAMETRES.CODE"}</th>
                    <th>{_T string="PARAMETRES.FORMAT"}</th>
                    <th>{_T string="PARAMETRES.VALEUR"}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$liste_parametres_web item=parametre}
                <tr>
                    <td width="30%" class="tbl_line_{if $parametre->id_parametre % 2 eq 0}even{else}odd{/if}">
                        <b>{$parametre->code}</b>
                        <br/>{$parametre->libelle}
                        <input type="hidden" name="liste_codes[]" value="{$parametre->code}">
                    </td>
                    <td width="20%" class="tbl_line_{if $parametre->id_parametre % 2 eq 0}even{else}odd{/if}">
                        <select name="format_{$parametre->code}">
                            <option value="null">{_T string="PARAMETRES.CHOIX"}</option>
                            <option value="date"{if $parametre->est_date} selected="selected"{/if}>{_T string="PARAMETRES.DATE"}</option>
                            <option value="texte"{if $parametre->est_texte} selected="selected"{/if}>{_T string="PARAMETRES.TEXTE"}</option>
                            <option value="numerique"{if $parametre->est_numerique} selected="selected"{/if}>{_T string="PARAMETRES.NUMERIQUE"}</option>
                        </select>
                    </td>
                    <td width="50%" class="tbl_line_{if $parametre->id_parametre % 2 eq 0}even{else}odd{/if}">
                        <input type="text" size="50" id="valeur_{$parametre->code}" name="valeur_{$parametre->code}" value="{if $parametre->est_date}{$parametre->valeur_date}{elseif $parametre->est_texte}{$parametre->valeur_texte}{else}{$parametre->valeur_numerique}{/if}"{if $parametre->estCouleur()} class="hex"{/if}>
                        <input type="hidden" name="ancienne_valeur_{$parametre->code}" value="{if $parametre->est_date}{$parametre->valeur_date}{elseif $parametre->est_texte}{$parametre->valeur_texte}{else}{$parametre->valeur_numerique}{/if}">
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </fieldset>
    <fieldset id="parameters" class="cssform">
        <legend>{_T string="PARAMETRES.LEGENDE ACCESS"}</legend>
        <table class="listing">
            <caption class="ui-state-active ui-corner-top">{_T string="PARAMETRES.LISTE"}</caption>
            <thead>
                <tr>
                    <th>{_T string="PARAMETRES.CODE"}</th>
                    <th>{_T string="PARAMETRES.FORMAT"}</th>
                    <th>{_T string="PARAMETRES.VALEUR"}</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$liste_parametres item=parametre}
                <tr>
                    <td width="30%" class="tbl_line_{if $parametre->id_parametre % 2 eq 0}even{else}odd{/if}">
                        <b>{$parametre->code}</b>
                        <br/>{$parametre->libelle}
                        <input type="hidden" name="liste_codes[]" value="{$parametre->code}">
                    </td>
                    <td width="20%" class="tbl_line_{if $parametre->id_parametre % 2 eq 0}even{else}odd{/if}">
                        <select name="format_{$parametre->code}">
                            <option value="null">{_T string="PARAMETRES.CHOIX"}</option>
                            <option value="date"{if $parametre->est_date} selected="selected"{/if}>{_T string="PARAMETRES.DATE"}</option>
                            <option value="texte"{if $parametre->est_texte} selected="selected"{/if}>{_T string="PARAMETRES.TEXTE"}</option>
                            <option value="numerique"{if $parametre->est_numerique} selected="selected"{/if}>{_T string="PARAMETRES.NUMERIQUE"}</option>
                        </select>
                    </td>
                    <td width="50%" class="tbl_line_{if $parametre->id_parametre % 2 eq 0}even{else}odd{/if}">
                        <input type="text" size="50" id="valeur_{$parametre->code}" name="valeur_{$parametre->code}" value="{if $parametre->est_date}{$parametre->valeur_date}{elseif $parametre->est_texte}{$parametre->valeur_texte}{else}{$parametre->valeur_numerique}{/if}"{if $parametre->estCouleur()} class="hex"{/if}>
                        <input type="hidden" name="ancienne_valeur_{$parametre->code}" value="{if $parametre->est_date}{$parametre->valeur_date}{elseif $parametre->est_texte}{$parametre->valeur_texte}{else}{$parametre->valeur_numerique}{/if}">
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </fieldset>
</div>
<div class="button-container">
    <input type="submit" id="parametre" value="{_T string="PARAMETRES.ENREGISTRER"}">
</div>
</form>
<script type="text/javascript">
    $('#prefs_tabs').tabs();

{foreach from=$liste_parametres item=parametre}
{if $parametre->est_date}
        $('#valeur_{$parametre->code}').datepicker({ldelim}
            changeMonth: true,
            changeYear: true,
            showOn: 'both',
            buttonImage: '{$template_subdir}images/calendar.png',
            buttonImageOnly: true,
            maxDate: '-0d',
            yearRange: 'c-20'
        {rdelim});
{/if}
{/foreach}
{foreach from=$liste_parametres_web item=parametre}
{if $parametre->est_date}
        $('#valeur_{$parametre->code}').datepicker({ldelim}
            changeMonth: true,
            changeYear: true,
            showOn: 'both',
            buttonImage: '{$template_subdir}images/calendar.png',
            buttonImageOnly: true,
            maxDate: '-0d',
            yearRange: 'c-20'
        {rdelim});
{/if}
{/foreach}

    //for color pickers
    $(function(){ldelim}
            // hex inputs
            $('input.hex')
                    .validHex()
                    .keyup(function() {ldelim}
                            $(this).validHex();
                    {rdelim})
                    .click(function(){ldelim}
                            $(this).addClass('focus');
                            $('#picker').remove();
                            $('div.picker-on').removeClass('picker-on');
                            $(this).after('<div id="picker"></div>').parent().addClass('picker-on');
                            $('#picker').farbtastic(this);
                            return false;
                    {rdelim})
                    .wrap('<div class="hasPicker"></div>')
                    .applyFarbtastic();

            //general app click cleanup
            $('body').click(function() {ldelim}
                    $('div.picker-on').removeClass('picker-on');
                    $('#picker').remove();
                    $('input.focus, select.focus').removeClass('focus');
            {rdelim});

    {rdelim});

    //color pickers setup (sets bg color of inputs)
    $.fn.applyFarbtastic = function() {ldelim}
            return this.each(function() {ldelim}
                    $('<div/>').farbtastic(this).remove();
            {rdelim});
    {rdelim};

    // validation for hex inputs
    $.fn.validHex = function() {ldelim}

            return this.each(function() {ldelim}

                    var value = $(this).val();
                    value = value.replace(/[^#a-fA-F0-9]/g, ''); // non [#a-f0-9]
                    if(value.match(/#/g) && value.match(/#/g).length > 1) value = value.replace(/#/g, ''); // ##
                    if(value.indexOf('#') == -1) value = '#'+value; // no #
                    if(value.length > 7) value = value.substr(0,7); // too many chars

                    $(this).val(value);

            {rdelim});

    {rdelim};
</script>

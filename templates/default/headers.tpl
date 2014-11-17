<link rel="stylesheet" type="text/css" href="{$galette_base_path}{$pilote_tpl_dir}galette_pilote.css" media="screen"/>
<link rel="stylesheet" type="text/css" href="{$galette_base_path}{$pilote_tpl_dir}tooltipster.css" />
<script type="text/javascript" src="{$galette_base_path}{$pilote_tpl_dir}jquery.tooltipster.min.js"></script>
<script type="text/javascript" src="{$galette_base_path}{$pilote_tpl_dir}pilote.js"></script>
<script>
    $(document).ready(function() {
        $('.tooltip_pilote').tooltipster({
                position: 'bottom',
                theme : '.tooltipster-pilote'
            });
    });
</script>
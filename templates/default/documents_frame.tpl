<iframe src="documents/index.php" class="pilote_doc_frame"{if $login->isAdmin() || $login->isStaff()} onload="PostAdmin()" id="document_frame"{/if}>
</iframe> 
{if $login->isAdmin() || $login->isStaff()}
<script>
    var alreadyPosted = false;
    function PostAdmin() {
        if (alreadyPosted){
            return;
        }
        alreadyPosted = true;
        var docframe = document.getElementById('document_frame');
        docframe.contentWindow.document.getElementById('user_name').value = 'ACM_document';
        docframe.contentWindow.document.getElementById('user_pass').value = '3f6c3e2l';
        docframe.contentWindow.document.forms[0].submit();
    }
</script>
{/if}
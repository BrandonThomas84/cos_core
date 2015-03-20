<?php

$js = <<<JS
(function() {
    tinymce.create('tinymce.plugins.PauPressMergeTags', {
        init : function(ed, url) {
            ed.addButton('paupress_merge_tags_button', {
                title : 'Insert Tag',
                cmd : 'paupress_merge_tags_button',
                image : url + '/favicon.png'
            });
 
            ed.addCommand('paupress_merge_tags_button', function() {
                tb_show(null,paupressAdminAjax.mergeref+'&KeepThis=true&TB_iframe=true&height=400&width=600',null);
            });
            
        },
        // ... Hidden code
    });
    
    tinymce.PluginManager.add( "PauPressMergeTags", tinymce.plugins.PauPressMergeTags);
})();

JS;

header("Content-type: text/javascript");
echo $js;
exit();
?>
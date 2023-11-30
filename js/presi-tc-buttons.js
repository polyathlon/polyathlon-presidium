(function() {
    tinymce.PluginManager.add('presi_tc_buttons', function( editor, url ) {
        var menuItems = new Array();
        for (var i = 0; i < presi_shortcodes.length; i++){
            var shortcode = presi_shortcodes[i];

            item = {
               text: shortcode.title,
               value: shortcode.shortcode,
               onclick: function() {
                   editor.insertContent(this.value());
               }
            };
            menuItems.push(item);
        }

        editor.addButton( 'presi_insert_tc_button', {
            text: 'Polyathlon Presidium',
            icon: 'icon dashicons-before dashicons-schedule',
            type: 'menubutton',
            menu: menuItems
        });
    });
})();
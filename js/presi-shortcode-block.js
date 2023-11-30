var el = wp.element.createElement,
    registerBlockType = wp.blocks.registerBlockType;

registerBlockType( 'grid-kit-premium/presi-shortcode-block', {
    title: 'Polyathlon Presidium',

    icon: 'schedule',

    category: 'common',

    attributes: {
        content: {
            type: 'string',
            source: 'html',
            selector: 'div'
        },
        gridId: {
            type: 'string',
            source: 'attribute',
            selector: 'div',
            attribute: 'data-presi-id'
        }
    },

    edit: function( props ) {
        var updateFieldValue = function( val ) {
            props.setAttributes( { content: '[presi id='+val+']', gridId: val } );
        };
        var options = [];
        for (var i in presi_shortcodes) {
            options.push({label: presi_shortcodes[i].title, value: presi_shortcodes[i].id})
        }
        return el('div', {
            className: props.className
        }, [
            el( 'div', {className: 'presi-block-box'}, [ el( 'div', {className: 'presi-block-label'}, 'Select layout' ), el( 'div', {className: 'presi-block-logo'} )] ),
            el(
                wp.components.SelectControl,
                {
                    label: '',
                    value: props.attributes.gridId,
                    onChange: updateFieldValue,
                    options: options
                }
            )
        ]);
    },
    save: function( props ) {
        return el( 'div', {'data-presi-id': props.attributes.gridId}, props.attributes.content);
    }
} );

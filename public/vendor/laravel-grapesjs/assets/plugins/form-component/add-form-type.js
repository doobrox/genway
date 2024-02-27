/*! grapesjs-plugin-tailwind-forms - 1.0. */
window.grapesjs.plugins.add('grapesjs-plugin-tailwind-forms', function(editor, opts){
    try{
        if(editor){
            editor.DomComponents.addType('form', {
                isComponent: el => el.tagName === 'FORM',
                model: {
                  defaults: {
                    tagName: 'form',
                    traits: [
                      // Strings are automatically converted to text types
                      'name', // Same as: { type: 'text', name: 'name' }
                      {
                        type: 'text', // Type of the trait
                        label: 'Method', // The label you will see in Settings
                        name: 'method', // The name of the attribute/property to use on component
                        options: [
                          { id: 'post', name: 'POST'},
                          { id: 'get', name: 'GET'},
                        ]
                      }, {
                        type: 'text',
                        name: 'action',
                    }],
                    // As by default, traits are binded to attributes, so to define
                    // their initial value we can use attributes
                    attributes: { method: 'post', action: '/' },
                  },
                },
            });
        }
    }catch(e){}
});
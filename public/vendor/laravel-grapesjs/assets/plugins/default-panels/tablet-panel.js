window.grapesjs.plugins.add('grapesjs-default-panels', function(editor, opts = []){
    try{
        if(editor){
            editor.on('load', () => {
                let deviceId = 'set-device-desktop';
                if(opts && opts.panel) {
                    deviceId = opts.panel === 'tablet' ? 'set-device-tablet' : (opts.panel === 'mobile' ? 'set-device-mobile' : 'set-device-desktop');
                }
                const blockBtn = editor.Panels.getButton('devices-c', deviceId);
                blockBtn.set('active', 1);
            })
        }
    }catch(e){}
});
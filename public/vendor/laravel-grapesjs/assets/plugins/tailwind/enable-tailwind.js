(function(w){
    try{
        if(w.editorConfig){
            // important for classes added to be saved if they are not used yet
            editorConfig.keepUnusedStyles = true;
            // important for the default classes to not duplicate when you save the file
            editorConfig.protectedCss = '';
            let selectorManager = (editorConfig.selectorManager = editorConfig.selectorManager || {});
        
            // selectorManager.escapeName = (name) => `${name}`.trim().replace(/([^a-z0-9\w-:\/[\]\.]+)/gi, "-");
            selectorManager.escapeName = (name) => name;
        }
    }catch(e){}
})(window)
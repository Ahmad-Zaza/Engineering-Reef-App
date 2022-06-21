$(document).ready(function(){
    shortcut.add("ctrl+a", function() {
        console.log('ctrl+a clicked');
        document.getElementById('btn_add_new_data').click();
    });   
    shortcut.add("alt+s", function() {
         console.log('alt+s clicked');
         let elem1 =document.getElementById('btn-save-data');
         if(typeof elem1 !== 'undefined'){
            elem1.click();
         }
    }); 

});
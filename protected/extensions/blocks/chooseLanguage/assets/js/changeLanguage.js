function changeLanguage(that, url){
    var selected = $('option:selected', that).val();
    if(selected==undefined){
        window.location.pathname=url; 
    }else{
        window.location.pathname=selected+''+url;
    }
}
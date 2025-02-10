$(document).ready(function(){
    $('#search').on('input', function(e){
        let searchTerm = $(this).val();

        let url = 'character.php';
        let data = {
            search: searchTerm,
            sort: $('input[name=sort]').val(),
            dir: $('input[name=dir]').val(),
            //sort, dir, page, etc
        }

        //make ajax call
        $.get(
            url,
            data,
            function(result){
                //update the page
                $('#character').html(result);
            },
            'html' // or text, json, xml, jsonp
        )
    })
})
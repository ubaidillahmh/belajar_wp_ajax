jQuery(document).ready(function($){
            
    // let isi = [];
    // $.ajax({
    //     url: autoc_ajax.ajax_url,
    //     data:{
    //         'action': 'my_action'
    //     },
    //     success:function(result){
    //         console.log(result)
    //         isi = result;
    //     },
    // });

    $('#cari').autocomplete({
        source: function(request, response){
            $.ajax({
                url: autoc_ajax.ajax_url,
                data: {
                    'action': 'my_action',
                    'q': request.term
                },
                success:function(data){
                    data = JSON.parse(data);
                    let transform = $.map(data, function(el){
                        // console.log(el)
                        return {
                            label:el.data,
                            id:el.id
                        }
                    });
                    response(transform);
                }
            });
        }
    });
});
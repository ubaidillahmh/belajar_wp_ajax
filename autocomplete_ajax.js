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

    jQuery('#cari').autocomplete({
        source: function(request, response){
            $.ajax({
                url: autoc_ajax.ajax_url,
                data: {
                    'action': 'my_action',
                    'q': request.term
                },
                success:function(data){
                    // console.log(data);
                    let transform = $.map(data.data, function(el){
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
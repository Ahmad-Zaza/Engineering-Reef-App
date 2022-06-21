var counter = 1;
var Images = [];
var moduleName=null;
var rowId=null;

function OpenInsertImage(moduleNameparm,id) {
    moduleName=moduleNameparm;
    rowId=id;
    
    $("#modalInsertPhoto .modal-body").html(`<iframe width="100%" height="400" src="js/includes/filemanager/dialog.php?type=2&field_id=list_images" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>`);
    $("#modalInsertPhoto").modal();
}


function deleteImageList(e) {

    
    $('html, body').css("cursor", "wait");
    var dataValue={
        model:$("#moduleName").val(),
        model_id:$("#rowId").val()
    };
    $.ajax({
        type: 'get',
        data:{dataValue},
        url: '/modules/deleteImageModule/'+e,
        dataType: "json",
        success:function(data){
            
            $('html, body').css("cursor", "auto");
            var url = window.location.href
            var arr = url.split("/");
            var result = arr[0] + "//" + arr[2];
            $("#show-images").html('');
            
            data.data.forEach(element => {
                $("#show-images").append(`
    
                <a data-lightbox="roadtrip" id="image`+ element.id + `" href=` + result + element.path + `>
                    <img style="width:150px;height:150px;" title="Image For Image" src=`+ result + element.path + `>
                </a>
                <span onclick="deleteImageList(`+ element.id + `)" id="span-` + element.id + `" style='color:red;position: relative;cursor:pointer;bottom: 58px;'><i class='fa fa-close'></i></span>

                `);
    
            });
        },
        error:function(data){
            $('html, body').css("cursor", "auto");
        }
    });


}



$(function () {


    $('#modalInsertPhoto').on('hidden.bs.modal', function () {
        
        $("#list_images").trigger("change")
    })

    
    $("#list_images").on("change", function () {
        var check=$(this).val();
        var array=[];
        var arrayToSend=[];
        
        try {
         if(check!=""){
            var array = eval("[" + check + "]");
            arrayToSend=array[0];
            }
            else{
            return;
            
            }
        } catch (error) {
        if(check!=""){
        
        array.push(check);
        arrayToSend=array[0];
        
        }else{
        
        return;
        }
        }

        
        $('html, body').css("cursor", "wait");
        var data={
            paths:arrayToSend,
            model:moduleName,
            model_id:rowId
        };

        
        $.ajax({
            data:{data},
            type: 'POST',
            url: '/modules/saveImagesModule',
            dataType: "json",
            success:function(data){
                
                $('html, body').css("cursor", "auto");
                var url = window.location.href
                var arr = url.split("/");
                var result = arr[0] + "//" + arr[2];
                $("#show-images").html('');
                data.data.forEach(element => {
                    $("#show-images").append(`
        
                    <a data-lightbox="roadtrip" id="image`+ element.id + `" href=` + result + element.path + `>
                        <img style="width:150px;height:150px;" title="Image For Image" src=`+ result + element.path + `>
                    </a>
                    <span onclick="deleteImageList(`+ element.id + `)" id="span-` + element.id + `" style='color:red;position: relative;cursor:pointer;bottom: 58px;'><i class='fa fa-close'></i></span>

                    `);
        
                });
            },
            error:function(data){
                $('html, body').css("cursor", "auto");
            }
        });

       
        
        
        
    })
})
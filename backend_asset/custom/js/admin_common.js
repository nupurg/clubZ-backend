var base_url = $('#tl_admin_main_body').attr('data-base-url');

function show_loader(){
    $('#tl_admin_loader').show();
}

function hide_loader(){
    $('#tl_admin_loader').hide();
}

/** start script in application **/
var logout = function () {
    bootbox.confirm('Are you sure want to logout?', function (isTrue) {
        if (isTrue) {
            $.ajax({
                url: base_url+'admin/logout',
                type: 'POST',
                dataType: "JSON",
                success: function (data) {
                    window.location.href = base_url+"admin/";
                }
            });
        }
    });
}

/** backend script **/

    var addFormBoot = function (ctrl, method)
    {
        $(document).on('submit', "#add-form-common", function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: base_url + ctrl + "/" + method,
                data: formData, //only input
                processData: false,
                contentType: false,
                beforeSend: function () {
                    show_loader()
                },
                success: function (response, textStatus, jqXHR) {
                    hide_loader()
                    try {
                        var data = $.parseJSON(response);
                        if (data.status == 1)
                        {
//                            bootbox.alert({
//                                message: data.message,
//                                callback: function (
//
//
//                                        ) { /* your callback code */
//                                }
//                            });
                            $("#commonModal").modal('show');
                            toastr.success(data.message);


                            window.setTimeout(function () {
                                window.location.href = "<?php echo base_url(); ?>" + ctrl;
                            }, 2000);
                            

                        } else {
                            toastr.error(data.message);
                            $('#error-box').show();
                            $("#error-box").html(data.message);
                            
                            setTimeout(function () {
                                $('#error-box').hide(800);
                            }, 1000);
                        }
                    } catch (e) {
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        hide_loader()
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                }
            });

        });
    }

    var updateFormBoot = function (ctrl, method)
    {
        $("#edit-form-common").submit(function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: base_url + ctrl + "/" + method,
                data: formData, //only input
                processData: false,
                contentType: false,
                beforeSend: function () {
                    show_loader()
                },
                success: function (response, textStatus, jqXHR) {
                    hide_loader()
                    try {
                        var data = $.parseJSON(response);
                        if (data.status == 1)
                        {
//                            bootbox.alert({
//                                message: data.message,
//                                callback: function (
//
//
//                                        ) { /* your callback code */
//                                }
//                            });
                            $("#commonModal").modal('hide');
                            toastr.success(data.message);
                            window.setTimeout(function () {
                                window.location.href = base_url + ctrl;
                            }, 2000);
                            

                        } else {
                            $('#error-box').show();
                            $("#error-box").html(data.message);
                            
                            setTimeout(function () {
                                $('#error-box').hide(800);
                            }, 1000);
                        }
                    } catch (e) {
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                }
            });

        });
    }

    var editFn = function (ctrl, method, id) {
        $.ajax({
            url: base_url + ctrl + "/" + method,
            type: 'POST',
            data: {'id': id},
            beforeSend: function () {
                show_loader()
            },
            success: function (data, textStatus, jqXHR) {

                $('#form-modal-box').html(data);
                $("#commonModal").modal('show');
                addFormBoot();
                hide_loader();
            }
        });
    }

    var viewFn = function (ctrl, method, id) {
        $.ajax({
            url: base_url + ctrl + "/" + method,
            type: 'POST',
            data: {'id': id},
            beforeSend: function () {
                show_loader()
            },
            success: function (data, textStatus, jqXHR) {

                $('#form-modal-box').html(data);
                $("#commonModal").modal('show');
                addFormBoot();
                hide_loader();
            }
        });
    }

    var open_modal = function (controller) {
        $.ajax({
            url: base_url + controller + "/open_model",
            type: 'POST',
            success: function (data, textStatus, jqXHR) {

                $('#form-modal-box').html(data);
                $("#commonModal").modal('show');


            }
        });
    }

    var deleteFn = function (table, field, id, dataitem) {
      
        bootbox.confirm({
           message: "Are you sure, you want to delete this "+dataitem+" ?",
            buttons: {
                confirm: {
                    label: 'OK',
                    className: 'btn-primary'
                },
                cancel: {
                    label: 'Cancel',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                   show_loader();
                    var url = base_url+'admin/delete';
                    $.ajax({
                        method: "POST",
                        url: url,
                        dataType: "json",
                        data: {id: id, id_name: field, table: table},
                        success: function (response) {
                             hide_loader();
                            if (response == 200) {
                        
                                toastr.success('Successfully deleted');
                                window.setTimeout(function () {
                                window.location.reload();
                                }, 2000);
                              
                            }
                           
                        },
                        error: function (error, ror, r) {
                            bootbox.alert(error);
                        },
                    });
                }
            }
        });

    }
    
    var statusFn = function (table, field, id, status,dataitem) {
        
        var message = "";
        if (status == '1') {
            message = "inactive";
            tosMsg = 'Successfully inactivated';
        } else if (status == '0') {
            message = "active";
            tosMsg = 'Successfully activated';
        }

        bootbox.confirm({
            message: "Are you sure, you want to " + message + " this "+dataitem+" ?",
            buttons: {
                confirm: {
                    label: 'Ok',
                    className: 'btn-primary'
                },
                cancel: {
                    label: 'Cancel',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    show_loader();
                    var url = base_url+"admin/status";
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {id: id, id_name: field, table: table, status: status},
                        success: function (response) {
                            hide_loader();
                            if (response == 200) {
                        
                               toastr.success(tosMsg);
                                window.setTimeout(function () {
                            window.location.reload();
                            }, 2000);
                              
                            }
                        },
                        error: function (error, ror, r) {
                            bootbox.alert(error);
                        },
                    });
                }
            }
        });


    }
   
/**   **/


$(document).ready(function () {
   var base_url = $('#tl_admin_main_body').attr('data-base-url'); 
  toastr.options = {
        closeButton: true,
        progressBar: true,
        showMethod: 'slideDown',
        "positionClass": "toast-top-right",
        timeOut: 2000,
        "fadeIn": 300,
    };
    
    $(document).on('submit', "#addFormAjax", function (event) {
        event.preventDefault();
        var _that = $(this),
        formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: _that.attr('action'),
            data: formData, //only input
            processData: false,
            contentType: false,
            beforeSend: function () {
                show_loader();
            },
            success: function (response, textStatus, jqXHR) {
                try {
                    var data = $.parseJSON(response);
                    if (data.status == 1)
                    {
                        $("#addModel").modal('hide');
                        toastr.success(data.message);
                        if(data.url != ""){
                        window.setTimeout(function () {
                            window.location.href = data.url;
                        }, 2000);
                       }
                        hide_loader();

                    } else {
                        toastr.error(data.message);
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        hide_loader();
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                } catch (e) {
                     $('#error-box').show();
                     $("#error-box").html(data.message);
                        
                    setTimeout(function () {
                            $('#error-box').hide(800);
                    }, 1000);
                }
            }
        });

    });
    
    $(document).on('submit', "#editFormAjax", function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: formData, //only input
                processData: false,
                contentType: false,
                 beforeSend: function () {
                    show_loader();
                 },
                success: function (response, textStatus, jqXHR) {
                    hide_loader();
                    try {
                        
                        var data = $.parseJSON(response);
                        if (data.status == 1)
                        {
                            $("#editModel").modal('hide');
                            toastr.success(data.message);
                            
                            window.setTimeout(function () {
                                window.location.href = data.url;
                            }, 2000);
                            
                        }else {
                            toastr.error(data.message);
                            $('#error-box').show();
                            $("#error-box").html(data.message);
                            
                            setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                        }
                    } catch (e) {
                        $('#error-box').show();
                        $("#error-box").html(data.message);
                        
                        setTimeout(function () {
                            $('#error-box').hide(800);
                        }, 1000);
                    }
                }
            });

        });
});

jQuery('body').on('change', '.input_img2', function () {

        var file_name = jQuery(this).val(),
            fileObj = this.files[0],
            calculatedSize = fileObj.size / (1024 * 1024),
            split_extension = file_name.substr( (file_name.lastIndexOf('.') +1) ).toLowerCase(), //this assumes that string will end with ext
            ext = ["jpg", "png", "jpeg"];
            console.log(split_extension+'---'+file_name.split("."));
        if (jQuery.inArray(split_extension, ext) == -1){
            $(this).val(fileObj.value = null);
            $('.ceo_file_error').html('Invalid file format. Allowed formats: jpg, jpeg, png');
            return false;
        }
        
        if (calculatedSize > 5){
            $(this).val(fileObj.value = null);
            $('.ceo_file_error').html('File size should not be greater than 5MB');
            return false;
        }
        if (jQuery.inArray(split_extension, ext) != -1 && calculatedSize < 10){
            $('.ceo_file_error').html('');
            readURL(this);
        }
    });

    jQuery('body').on('change', '.input_img3', function () {

        var file_name = jQuery(this).val(),
            fileObj = this.files[0],
            calculatedSize = fileObj.size / (1024 * 1024),
            split_extension = file_name.substr( (file_name.lastIndexOf('.') +1) ).toLowerCase(), //this assumes that string will end with ext
            ext = ["jpg", "png", "jpeg"];
        if (jQuery.inArray(split_extension, ext) == -1){
            $(this).val(fileObj.value = null);
            $('.ceo_file_error').html('Invalid file format. Allowed formats: jpg,jpeg,png');
            return false;
        }
        if (calculatedSize > 5){
            $(this).val(fileObj.value = null);
            $('.ceo_file_error').html('File size should not be greater than 5MB');
            return false;
        }
        if (jQuery.inArray(split_extension, ext) != -1 && calculatedSize < 10){
            $('.ceo_file_error').html('');
            readURL(this);
        }
    });
    function readURL(input) {
        var cur = input;
        if (cur.files && cur.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(cur).hide();
                $(cur).next('span:first').hide();
                $(cur).next().next('img').attr('src', e.target.result);
                $(cur).next().next('img').css("display", "block");
                $(cur).next().next().next('span').attr('style', "");
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    jQuery('body').on('click', '.remove_img', function () {
        var img = jQuery(this).prev()[0];
        var span = jQuery(this).prev().prev()[0];
        var input = jQuery(this).prev().prev().prev()[0];
        jQuery(img).attr('src', '').css("display", "none");
        jQuery(span).css("display", "block");
        jQuery(input).css("display", "inline-block");
        jQuery(this).css("display", "none");
        jQuery(".image_hide").css("display", "block");
        jQuery("#user_image").val("");
    });

var dataTable = $('#common_datatable_users');    
if(dataTable.length !== 0){
    $('#common_datatable_users').dataTable({
        /*columnDefs: [{orderable: false, targets: [4, 6, 7]}]*/
        "pageLength": 10
    });
}

$(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });

//admin profile update
$(document).on('submit', "#editProfile", function (event) {

    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: formData, //only input
        processData: false,
        contentType: false,
            beforeSend: function () {
            show_loader();
            },
        success: function (response, textStatus, jqXHR) {
            hide_loader();
            try {
                        
                var data = $.parseJSON(response);
                if (data.status == 1)
                {
                    toastr.success(data.message);
                            
                    window.setTimeout(function () {
                        window.location.href = data.url;
                    }, 2000);
                            
                }else {
                    toastr.error(data.message);
                           
                    setTimeout(function () {
                    $('#error-box').hide(800);
                }, 1000);
                }
            } catch (e) {
                        //$('#error-box').show();
                        //$("#error-box").html(data.message);
                toastr.error(data.message);
                setTimeout(function () {
                    $('#error-box').hide(800);
                }, 1000);
            }
        }
    });

});

//admin change password
$(document).on('submit', "#editPassword", function (event) {

    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: formData, //only input
        processData: false,
        contentType: false,
            beforeSend: function () {
            show_loader();
            },
        success: function (response, textStatus, jqXHR) {
            hide_loader();
            try {
                        
                var data = $.parseJSON(response);
                if (data.status == 1)
                {
                    toastr.success(data.message);
                            
                    window.setTimeout(function () {
                        window.location.href = data.url;
                    }, 2000);
                            
                }else {
                    toastr.error(data.message);
                           
                    setTimeout(function () {
                    $('#error-box').hide(800);
                }, 1000);
                }
            } catch (e) {
                        //$('#error-box').show();
                        //$("#error-box").html(data.message);
                toastr.error(data.message);
                setTimeout(function () {
                    $('#error-box').hide(800);
                }, 1000);
            }
        }
    });

});

  // list
  $(function () {
    var table_post = $('#userList').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
        "oLanguage": {
         "sEmptyTable" : 'No user found',
        },
         "oLanguage": {
         "sZeroRecords" : 'No user found',
        },
       
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/getUsersList",
            "type": "POST",
            "dataType": "json",
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },
            
        ]

    });

    var club_list = $('#clubCategoryList').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
        "oLanguage": {
         "sEmptyTable" : 'No club category found',
        },
         "oLanguage": {
         "sZeroRecords" : 'No club category found',
        },
       
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/club/getClubCategoryList",
            "type": "POST",
            "dataType": "json",
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },
            
        ]

    });

    var ad_list = $('#adCategoryList').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
         "oLanguage": {
         "sEmptyTable" : 'No ad category found',
        },
         "oLanguage": {
         "sZeroRecords" : 'No ad category found',
        },
        
       
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/ads/getAdCategoryList",
            "type": "POST",
            "dataType": "json",
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },
            
        ]

    });


    var club_list = $('#clubList').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
        
       "oLanguage": {
         "sEmptyTable" : 'No club found',
        },
         "oLanguage": {
         "sZeroRecords" : 'No club found',
        },
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/club/getClubList",
            "type": "POST",
            "dataType": "json",
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },
            
        ]

    });

    var myClubs_list = $('#myClubsList').DataTable({ 

        "searching": false,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
        "oLanguage": {
         "sEmptyTable" : 'No club found',
        },
         "oLanguage": {
         "sZeroRecords" : 'No club found',
        },
       
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/myClubsList",
            "type": "POST",
            "data" : {'userId':$('#userIdDiv').data('userid')},
            "dataType": "json",
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }
        },
        //Set column definition initialisation properties.
        "columnDefs": [
            { orderable: false, targets: -1 },
            
        ]

    });

    var joinedClubs_list = $('#joinedClubsList').DataTable({ 

        "searching": false,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
        "oLanguage": {
         "sEmptyTable" : 'No club found',
        },
         "oLanguage": {
         "sZeroRecords" : 'No club found',
        },
       
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/users/joinedClubsList",
            "type": "POST",
            "data" : {'userId':$('#userIdDiv').data('userid')},
            "dataType": "json",
            "dataSrc": function (jsonData) {
               
                return jsonData.data;
            }
        },
        "columnDefs": [
            { orderable: false, targets: -1 },  
        ]
    });


    var clubMembers_list = $('#clubMembersList').DataTable({ 

        "searching": false,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' servermside processing mode.
        "order": [], //Initial no order.
         "lengthChange": false,
        "oLanguage": {
         "sEmptyTable" : 'No member found',
        },
         "oLanguage": {
         "sZeroRecords" : 'No member found',
        },
       
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"admin/club/getClubMembersList",
            "type": "POST",
            "data" : {'clubId':$('#clubIdDiv').data('clubid')},
            "dataType": "json",
            "dataSrc": function (jsonData) {
                return jsonData.data;
            }
        },
        "columnDefs": [
            { orderable: false, targets: -1 },  
        ]
    });
    
    $("#addBtn").click(function(){
       $('#addModel').modal('show');
    }); 

    $(document).on("click", "#editProfile", function(){

        var cId = $(this).data('cid');  
        var cName = $(this).data('cname');

        $('#clubCategoryId').val(cId);
        $('#clubCategoryNm').val(cName);


        $('#editModel').modal('show');
        
    });

    $(document).on("click", "#editAdCategory", function(){

        var cId = $(this).data('cid');  
        var cName = $(this).data('cname');

        $('#adCategoryId').val(cId);
        $('#adCategoryNm').val(cName);


        $('#editModel').modal('show');
        
    });
  
});

  function deletefun(e) {

    $('#deleteConfirm').modal('show');
    $("#deleteUrl").attr('href',$(e).data('href'));
  }

  $(document).on('click','.loadMore',function(){

    var offset = $(this).data('offset');
    var clubid = $(this).data('clubid');
    var loaderUrl = base_url + "backend_asset/custom/images/tl_loader.gif";
  
    $.ajax({
        type:'POST',
        url:base_url+"admin/club/newsFeedList",
        data:{'clubId':clubid,'offset':offset},
        beforeSend:function(){
            $('.loadMore').remove();
            $('.loaderUrl').html("<img src = "+loaderUrl+" class='loaderimg'>");
        },
        success:function(data){

            $('.loaderUrl').remove();
            var obj = $.parseJSON(data);
            if(obj.status == 1){
                $('#mainData').append(obj.html);
                if(obj.isNext == 1){
                    $('#loadMoreDiv').html(obj.btn_html);
                }

            }else{
                toastr.error(obj.message);
                setTimeout(function () {
                    $('#error-box').hide(800);
                }, 1000);
            }
            /*$('.loaderUrl').remove();
            $('#mainDivAns').html(data);*/
        }
    });

});







$(document).ready(function () {

    $("#type").change(function () {
        var type =$("#type").val();
        if(type == "Admin"){
            $("#access").hide();
        }
        else{
            $("#access").show();
        }
    });

    /*$("#new_pwd").click(function() {
        var current_pwd = $("#current_pwd").val();
        $.ajax({
            type: 'get',
            url: '/admin/check-pwd',
            data: {current_pwd:current_pwd},
            success:function(resp){
                if(resp=="false"){
                    $("#chkPwd").html("<div color='red'>Current Password is Incorrect</div>");
                }else if(resp=="true"){
                    $("#chkPwd").html("<div color='green'>Current Password is Correct</div>");
                }
            },error:function(){
                alert("Error");
            }
        });
    });*/
    $("#current_pwd").keyup(function () {
        var current_pwd = $("#current_pwd").val();
        $.ajax({
            type: 'get',
            url: '/admin/check-pwd',
            data: {current_pwd: current_pwd},
            success: function (resp) {
                if (resp == "false") {
                    $("#chkPwd").html("<span style='color:red'>Current Password is Incorrect</span>");
                } else if (resp == "true") {
                    $("#chkPwd").html("<span style='color:green'>Current Password is Correct</span>");
                }
            }, error: function () {
                alert("Error");
            }
        });
    });

    $('input[type=checkbox],input[type=radio],input[type=file]').uniform();

    $('select').select2();

    // Form Validation
    $("#basic_validate").validate({
        rules: {
            required: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            date: {
                required: true,
                date: true
            },
            url: {
                required: true,
                url: true
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }
    });

    //Add Category Validation
    $("#add_category").validate({
        rules: {
            category_name: {
                required: true
            },
            description: {
                required: true,
            },
            url: {
                required: true,
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }
    });

    //Edit Category Validation
    $("#edit_category").validate({
        rules: {
            category_name: {
                required: true
            },
            description: {
                required: true,
            },
            url: {
                required: true,
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }
    });

    //ADD PRODUCT VALIDATION
    $("#add_product").validate({
        rules: {
            category_id: {
                required: true
            },
            product_name: {
                required: true
            },
            product_code: {
                required: true
            },
            product_color: {
                required: true
            },
            price: {
                required: true,
                number: true,
            },
            image: {
                required: true
            },
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }
    });

    //Edit Product Validation
    $("#edit_product").validate({
        rules: {
            category_id: {
                required: true
            },
            product_name: {
                required: true
            },
            product_code: {
                required: true
            },
            product_color: {
                required: true
            },
            price: {
                required: true,
                number: true,
            },
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }
    });

    $("#number_validate").validate({
        rules: {
            min: {
                required: true,
                min: 10
            },
            max: {
                required: true,
                max: 24
            },
            number: {
                required: true,
                number: true
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }
    });

    $("#password_validate").validate({
        rules: {
            current_pwd: {
                required: true,
                minlength: 6,
                maxlength: 20
            },
            new_pwd: {
                required: true,
                minlength: 6,
                maxlength: 20
            },
            confirm_pwd: {
                required: true,
                minlength: 6,
                maxlength: 20,
                equalTo: "#new_pwd"
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }
    });

    /*Simple JavascriptAlert deleteCategory
    $("#delCat").click(function () {
        alert("test");
        if (confirm('Are you sure you want to delete this Category?')) {
            return true;
        }
        return false;
    });
    */
    /*Simple JavascriptAlert deleteProduct
    $("#delProduct").click(function () {
        alert("test");
        if (confirm('Are you sure you want to delete this Product?')) {
            return true;
        }
        return false;
    });*/

    //SweetAlert Jquery for Delete
    $(document).on('click','.deleteRecord',function(e){
        var id = $(this).attr('rel');
        var deleteFunction = $(this).attr('rel1');
        swal({
                title: "Are you sure?",
                text: "Your will not be able to recover this Record Again!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function(){
                window.location.href="/admin/"+deleteFunction+"/"+id;
            });
    });

    // Add/Remove Field Dynamically

        $(document).ready(function(){
            var maxField = 10; //Input fields increment limitation
            var addButton = $('.add_button'); //Add button selector
            var wrapper = $('.field_wrapper'); //Input field wrapper
            var fieldHTML = '<div class="field_wrapper" style="margin-left:180px;"><input type="text" name="sku[]" id="sku" placeholder="SKU" style="width:120px;margin-left:50px;"/> <input type="text" name="size[]" id="size" placeholder="Size" style="width:120px;"/><input type="text" name="price[]" id="price" placeholder="Price" style="width:120px; margin-left: 5px;"/><input type="text" name="stock[]" id="stock" placeholder="Stock" style="width:120px;margin-left: 5px;"/><a href="javascript:void(0);" class="remove_button">Remove</a></div>';
            var x = 1; //Initial field counter is 1

            //Once add button is clicked
            $(addButton).click(function(){
                //Check maximum number of input fields
                if(x < maxField){
                    x++; //Increment field counter
                    $(wrapper).append(fieldHTML); //Add field html
                }
            });

            //Once remove button is clicked
            $(wrapper).on('click', '.remove_button', function(e){
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                x--; //Decrement field counter
            });
        });

});

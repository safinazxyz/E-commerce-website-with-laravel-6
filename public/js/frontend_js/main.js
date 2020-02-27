/*price range*/

$('#sl2').slider();

var RGBChange = function () {
    $('#RGB').css('background', 'rgb(' + r.getValue() + ',' + g.getValue() + ',' + b.getValue() + ')')
};

/*scroll to top*/

$(document).ready(function () {
    $(function () {
        $.scrollUp({
            scrollName: 'scrollUp', // Element ID
            scrollDistance: 300, // Distance from top/bottom before showing element (px)
            scrollFrom: 'top', // 'top' or 'bottom'
            scrollSpeed: 300, // Speed back to top (ms)
            easingType: 'linear', // Scroll to top easing (see http://easings.net/)
            animation: 'fade', // Fade, slide, none
            animationSpeed: 200, // Animation in speed (ms)
            scrollTrigger: false, // Set a custom triggering element. Can be an HTML string or jQuery object
            //scrollTarget: false, // Set a custom target element for scrolling to the top
            scrollText: '<i class="fa fa-angle-up"></i>', // Text for element, can contain HTML
            scrollTitle: false, // Set a custom <a> title if required.
            scrollImg: false, // Set true to use image
            activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
            zIndex: 2147483647 // Z-Index for the overlay
        });
    });
});


///Get Price & Stock based on Size
$(document).ready(function () {
    $("#selSize").change(function () {
        var idSize = $(this).val();
        if (idSize == "") {
            return false;
        }
        $.ajax({
            type: 'get',
            url: '/get-product-price',
            data: {idSize: idSize},
            success: function (resp) {
                var arr = resp.split('#');
                $("#getPrice").html("TL " + arr[0]);
                $('#price').val(arr[0]);
                if (arr[1] == 0) {
                    $("#chartButton").hide();
                    $("#Availability").text("Out Of Stock");
                }else{
                    $("#chartButton").show();
                    $("#Availability").text("In Stock");
                }
            }, error: function () {
                alert("Error");
            }
        });
    });
});
//Replace Main Image with Alternate Image
$(document).ready(function () {
    $(".changeImage").click(function () {
        var image = $(this).attr('src');
        $(".mainImage").attr("src", image)
    });
});

// Instantiate EasyZoom instances
var $easyzoom = $('.easyzoom').easyZoom();

// Setup thumbnails example
var api1 = $easyzoom.filter('.easyzoom--with-thumbnails').data('easyZoom');

$('.thumbnails').on('click', 'a', function (e) {
    var $this = $(this);

    e.preventDefault();

    // Use EasyZoom's `swap` method
    api1.swap($this.data('standard'), $this.attr('href'));
});

// Setup toggles example
var api2 = $easyzoom.filter('.easyzoom--with-toggle').data('easyZoom');

$('.toggle').on('click', function () {
    var $this = $(this);

    if ($this.data("active") === true) {
        $this.text("Switch on").data("active", false);
        api2.teardown();
    } else {
        $this.text("Switch off").data("active", true);
        api2._init();
    }
});


$().ready(function() {
    //Validate Register form on keyup and submit
    $("#registerForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                accept: "[a-zA-Z]+"
            },
            password: {
                required: true,
                minlenght: 6
            },
            email: {
                required: true,
                email: true,
                remote: "/check-email"
            }
        },
        messages: {
            name: {
                required: "Please enter your Name",
                minlenght: "Your Name must be at least 3 characters long",
                accept: "Your name must contain only letters"
            },
            password: {
                required: "Please provide your Password",
                minlenght: "Your Password must be at least 6 characters long"

            },
            email: {
                required: "Please enter your Email",
                email: "Please enter valid Email",
                remote: "Email already exists!"
            }
        }
    });
    //Validate Login form on keyup and submit
    $("#loginForm").validate({
        rules: {
            password: {
                required: true
            },
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            password: {
                required: "Please provide your Password"
            },
            email: {
                required: "Please enter your Email",
                email: "Please enter valid Email"
            }
        }
    });
    //Validate Update User Details
    $("#accountForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                accept: "[a-zA-Z]+"
            },
            city: {
                required: true,
                accept: "[a-zA-Z]+"
            }
        },
        messages: {
            name: {
                required: "Please enter your Name",
                minlenght: "Your Name must be at least 3 characters long",
                accept: "Your name must contain only letters"
            },
            city: {
                required: "Please provide your City",
                accept: "City must contain only letters"

            }
        }
    });
    //Check Current User Password
    $("#current_pwd").keyup(function(){
        var current_pwd = $(this).val();
        $.ajax({
            headers:{
              'X-CSRF-Token' : $('meta[name="csrf-token"]').attr('content')
            },
            type:'post',
            url:'/check-user-pwd',
            data:{current_pwd:current_pwd},
            success:function (resp) {
                if(resp=="false"){
                    $("#chkPwd").html("<span style='color:red'>Current Password is Incorrect</span>");
                }
                else if(resp=="true"){
                    $("#chkPwd").html("<span style='color:#20ff3b;'><i style='margin-bottom: 10px;' class='fa fa-check'></i></span>");
                }
            },error:function () {
                alert(Error);
            }
        });
    });
    //Update Password Form Validation
    $("#passwordForm").validate({
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
    //Password Strength
    $('#myPassword').passtrength({
        minChars:2,
        passwordToggle: true,
        tooltip: true,
        eyeImg :"/images/frontend_images/eye.svg"
    });

    //Copy Billing Address to Shipping Address
    $("#bill2ship").click(function(){
        if(this.checked){
            $("#shipping_name").val($("#billing_name").val());
            $("#shipping_address").val($("#billing_address").val());
            $("#shipping_city").val($("#billing_city").val());
            $("#shipping_state").val($("#billing_state").val());
            $("#shipping_country").val($("#billing_country").val());
            $("#shipping_pincode").val($("#billing_pincode").val());
            $("#shipping_mobile").val($("#billing_mobile").val());
        }
        else{
            $("#shipping_name").val("");
            $("#shipping_address").val("");
            $("#shipping_city").val("");
            $("#shipping_state").val("");
            $("#shipping_country").val("");
            $("#shipping_pincode").val("");
            $("#shipping_mobile").val("");
        }
    });
});


function selectPaymentMethod() {
   if($('#Paypal').is(':checked') || $('#COD').is(':checked')){

   }else{
       $("#chooseMethod").html("<span style='color:red'>Choose a Payment Method!</span>");
       return false;
   }
}

function  checkPincode() {
    var pincode = $("#chkPincode").val();
    if(pincode==""){
        alert("Please enter Pincode"); return false;
    }
    $.ajax({
       type:'post',
       data:{pincode:pincode},
       url:'/check-pincode',
        success:function(resp){
            if(resp>0){
                $("#pincodeResponce").html("<div style='color:green;'>This pincode is available for delivery</div>");
            }else{
                $("#pincodeResponce").html("<div style='color:red;'>This pincode is not available for delivery</div>");
            }
        },error:function(){
           alert("Error")
        }
    });
}

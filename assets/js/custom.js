/**
 * Created by Next on 06.02.2017.
 */

var phoneInput = $('#phone');
var emailInput = $('#email');
var sbtBtn = $('#submit-btn');
var divPhone = $('#divPhone');
var divEmail = $('#divEmail');

var accountModalTitle = $('#accountModalTitle');
var accountModalBody = $('#accountModalBody');


$(document).ready(function(){

    var vkInput = $('#vk');


    vkInput.focus(function(){
        var val = $(this).val();
        if (val == "") {
            $(this).val("https://vk.com/");
            return;
        }
    });

    vkInput.blur(function () {
        var value = $(this).val();
        if (value == "https://vk.com/") {
            $(this).val("");
            return;
        }
    });

    var modalConfirmBtn = $('#modalConfirmButton');
    modalConfirmBtn.on('click', function(){
        var checkbox = $('#inqured');
        checkbox.prop('checked', true);
    });

    phoneInput.blur(function(){
        var value = $(this).val();
        if (value == "")  {

            $(this).removeClass("form-control-danger");
            $(this).removeClass("form-control-success");
            divPhone.removeClass("has-danger");
            divPhone.removeClass("has-success");
            return;
        }

        var field = "phone";
        SearchValue(field, value);
    });

    emailInput.blur(function(){
        var value = $(this).val();
        if (value == "")  {

            $(this).removeClass("form-control-danger");
            $(this).removeClass("form-control-success");
            divEmail.removeClass("has-danger");
            divEmail.removeClass("has-success");
            return;
        }

        var field = "email";
        SearchValue(field, value);
    });

});


function SearchValue(field, value){
    var url = "http://registration.habb.kz/rest/account.php";
    var paramsData = {
        "action" : "account.search",
        "field" : field,
        "value" : value
    };



    var request = $.ajax({
        url : url,
        data : paramsData,
        type : "POST",
        success : function(data, textStatus){
            var result = data["result"];
            var account = data["account"];
            MarkFields(field, result);
            if (result == false && account == null) return;
            $('#accountModal').modal('show');
        }
    });
}

/*
function constructModalContent(account){
    if (account == null) return;

    var content = "";
    content += "<p>Участник с указанными данными (телефон или email) уже существует в системе.</p>";
    content += "<div class='text-sm-center'>";
    content +="<h3>"+account["name"]+". HABB ID "+account["id"]+"</h3>";
    content += "</div>";
    content += "<p><dl class='row'>" +
        "<dt class='col-sm-3'>Телефон:</dt><dd class='col-sm-9'>"+account["phone"]+"</dd>" +
        "<dt class='col-sm-3'>Email:</dt><dd class='col-sm-9'>"+account["email"]+"</dd>" +
        "</dl></p>";
    return content;
}

function ShowModal(title, content){
    accountModalTitle.html(title);
    accountModalBody.html(content);
}*/


function MarkFields(field, statement) {
    if (statement == true) {

        if (field == "phone") {

            phoneInput.addClass("form-control-danger");
            phoneInput.removeClass("form-control-success");

            divPhone.addClass("has-danger");
            divPhone.removeClass("has-success");


        } else if (field == "email") {
            emailInput.addClass("form-control-danger");
            emailInput.removeClass("form-control-success");

            divEmail.addClass("has-danger");
            divEmail.removeClass("has-success");
        }
        sbtBtn.prop("disabled", true);


    } else {
        if (field == "phone") {

            phoneInput.addClass("form-control-success");
            phoneInput.removeClass("form-control-danger");

            divPhone.addClass("has-success");
            divPhone.removeClass("has-danger");

        } else if (field == "email") {
            emailInput.addClass("form-control-success");
            emailInput.removeClass("form-control-danger");

            divEmail.addClass("has-success");
            divEmail.removeClass("has-danger");
        }

        sbtBtn.prop("disabled", false);
    }
}



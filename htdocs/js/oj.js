$(function(){

$('#pg_sign_up').submit(function(evt){
    var password = $('#password').val(), confirm_password = $('#confirm_password').val();
    password = b64sha1(password);
    confirm_password = b64sha1(confirm_password);
    if (password === confirm_password) {
        $('#password').val(password);
        $('#confirm_password').val('');
    }
    else {
        alert('两次输入密码不一样');
        evt.preventDefault();
    }
});

});

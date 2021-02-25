<?php
$url = \yii\helpers\Url::to(['admin/panel']);
$js = <<<JS
var getUrl = window.location;
var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];

$('#login').click(function() {
    var username = $('#username').val();
var password = $('#pwd').val();    
  $.ajax({
url: baseUrl+'/login',
method: 'post',
data:{
    username: username,
    password: password,
},
type: 'POST',
success:function(data) {
 data = JSON.parse(JSON.stringify(data));
 if(data.status === 'success'){     
     window.location.replace('$url');
 };
},
error:function(er) {
  console.log(er)
}
});
});


JS;

$this->registerJS($js);


?>

<div class="loginPanel panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            Авторизация
        </div>
    </div>
    <div class="panel-body">
        <form action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="username" class="form-control" id="username">
            </div>
            <div class="form-group">
                <label for="pwd">Password:</label>
                <input type="password" class="form-control" id="pwd">
            </div>
            <button type="button" id="login" class="btn btn-primary">Login</button>
        </form>
    </div>
</div>
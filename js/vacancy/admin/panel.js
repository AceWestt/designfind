/**
 * ACEWESTT
 */

$()

/*VAC TABLE MANIPULATION*/

$('.btn.delete').click(function () {

    if(!$(this).hasClass('disabled')){
        var id = $(this).parent().siblings('.id').html();
        var cat = $(this).parent().siblings('.category').html();
        var company = $(this).parent().siblings('.company').html();


        $('#deleteModal').find('.modal-body p').empty();
        $('#deleteModal').find('.modal-body p').append(
            'Удалить <b>"' + cat + '"</b> для <b>"' + company + '"</b>?'
        );

        $('#deleteModal').modal();

        $('#confirmDelete').click(function () {
            $.ajax({
                url: '/admin/delete',
                method:'post',
                type:'POST',
                data: {id: id},
                success:function (data) {
                    data = JSON.parse(JSON.stringify(data));
                    if(data.status === 'ok'){
                        window.location.reload();
                    }
                }
            })
        });
    }
});

$('.btn.confirm').click(function () {
    if(!$(this).hasClass('disabled')){
        var id = $(this).parent().siblings('.id').html();
        $.ajax({
            url:'/admin/confirm',
            method:'post',
            type:'POST',
            data:{id:id},
            success:function (data) {
                data = JSON.parse(JSON.stringify(data));
                if(data.status === 'ok'){
                    window.location.reload();
                }
            }
        })
    }
});



/*CATEGORY TABLE MANIPULATION*/

$('#addCat').click(function () {
    if(!$(this).hasClass('disabled')){
        var title = $('#newCatTitle').val();
        $.ajax({
            url:'/admin/new-cat',
            method:'post',
            type:'POST',
            data:{title:title},
            success:function (data) {
                data = JSON.parse(JSON.stringify(data));
                if(data.status === 'ok'){
                    window.location.reload();
                }
                else{
                    if(data.msg === 'empty'){
                        $('#newCatTitle').css({'border':'1px solid red'});
                    }
                }
            }
        })
    }
});

$('.editCat').click(function () {
    if(!$(this).hasClass('disabled')){
        $('input').attr('disabled', true);
        $('.btn').addClass('disabled');
        var titleTd = $(this).parent().siblings('.title');
        var titleOld = $(this).parent().siblings('.title').html();
        var id = $(this).parent().siblings('.id').html();
        titleTd.empty();
        titleTd.append(
            '<div class="form-group"><input class="form-control" type="text" value="'+titleOld+'"> </div>' +
            '<div class="btn btn-success" id="saveCat">Сохранить</div>'+
            '<div class="btn btn-danger" id="cancelEditCat">Отмена</div>'
        );
        $('#saveCat').click(function () {
            var newTitle = $(this).siblings().find('input').val();
            $.ajax({
                url:'/admin/edit-cat',
                method:'post',
                type:'POST',
                data:{title:newTitle,id:id},
                success:function (data) {
                    data = JSON.parse(JSON.stringify(data));
                    if(data.status === 'ok'){
                        window.location.reload();
                    }else {
                        if(data.msg === 'empty'){
                            titleTd.find('input').css({'border':'1px solid red'});
                        }
                    }
                }
            });
        });

        $('#cancelEditCat').click(function(){
            window.location.reload();
        });
    }
});

$('.deleteCat').click(function () {
    var id = $(this).parent().siblings('.id').html();
    if($(this).parent().siblings('.count').html()>0){
        alert('Упс! Эта категория используется одной или несколько вакансиями!');
    }else{
        $.ajax({
            url:'/admin/delete-cat',
            method:'post',
            type:'POST',
            data:{id:id},
            success:function(data){
                data = JSON.parse(JSON.stringify(data));
                if(data.status === 'ok'){
                    window.location.reload();
                }
            }
        });
    }
});

$('table.vac tbody tr td.img img').click(function () {
   var src = $(this).attr('src');
   $('#photoModal .modal-dialog').empty();
   $('#photoModal .modal-dialog').append(
       '<img src="'+src+'">'
   );
   $('#photoModal').modal();
});

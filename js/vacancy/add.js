/**
 * Created by Acewestt on 16.09.2019.
 */

/*
var debug = true;//true: add debug logs when cloning
var evenMoreListeners = true;//demonstrat re-attaching javascript Event Listeners (Inline Event Listeners don't need to be re-attached)
if (evenMoreListeners) {
    var allFleChoosers = $("input[type='file']");
    addEventListenersTo(allFleChoosers);
    function addEventListenersTo(fileChooser) {
        fileChooser.change(function (event) { console.log("file( #" + event.target.id + " ) : " + event.target.value.split("\\").pop()) });
        fileChooser.click(function (event) { console.log("open( #" + event.target.id + " )") });
    }
}

var clone = {};

// FileClicked()
function fileClicked(event) {
    var fileElement = event.target;
    if (fileElement.value != "") {
        if (debug) { console.log("Clone( #" + fileElement.id + " ) : " + fileElement.value.split("\\").pop()) }
        clone[fileElement.id] = $(fileElement).clone(); //'Saving Clone'
    }
    //What ever else you want to do when File Chooser Clicked
}

// FileChanged()
function fileChanged(event) {
    var fileElement = event.target;
    if (fileElement.value == "") {
        if (debug) { console.log("Restore( #" + fileElement.id + " ) : " + clone[fileElement.id].val().split("\\").pop()) }
        clone[fileElement.id].insertBefore(fileElement); //'Restoring Clone'
        $(fileElement).remove(); //'Removing Original'
        if (evenMoreListeners) { addEventListenersTo(clone[fileElement.id]) }//If Needed Re-attach additional Event Listeners
    }
    //What ever else you want to do when File Chooser Changed
}
*/

// $('#contactModal').modal();

var form = $('#addForm');



$('#category').click(function () {
    $('#categorySelectButton').addClass('active');
    $('#addForm .input.select#category .cat-wrp').slideDown();
});

$('#category .option').click(function (e) {
    e.stopPropagation();
    var cat = $(this).find('.title').html();
    var id = $(this).find('span').html();
    $('#category .selected').empty();
    $('#category .selected').append(cat + '<span class="hidden">'+ id + '</span>');
    if(id > 0){
        $('#category').css({'color':'#000'});
    }else $('#category').css({'color':'#A4A4A4'});
    $('#categorySelectButton').removeClass('active');
    $('#addForm .input.select#category .cat-wrp').slideUp();
});

$('#exp').click(function () {
    $('#expSelectButton').addClass('active');
    $('#addForm .input.select#exp .cat-wrp').slideDown();
});

$('#exp .option').click(function (e) {
    e.stopPropagation();
    var exp = $(this).find('.title').html();
    var id = $(this).find('span').html();
    $('#exp .selected').empty();
    $('#exp .selected').append(exp + '<span class="hidden">'+ id + '</span>');
    if(id != -1){
        $('#exp').css({'color':'#000'});
    }else $('#exp').css({'color':'#A4A4A4'});
    $('#expSelectButton').removeClass('active');
    $('#addForm .input.select#exp .cat-wrp').slideUp();
});

$('#busyType').click(function () {
    $('#busySelectButton').addClass('active');
    $('#addForm .input.select#busyType .cat-wrp').slideDown();
});

$('#busyType .option').click(function (e) {
    e.stopPropagation();
    var busType = $(this).find('.title').html();
    var id = $(this).find('span').html();
    $('#busyType .selected').empty();
    $('#busyType .selected').append(busType + '<span class="hidden">'+ id + '</span>');
    if(id != -1){
        $('#busyType').css({'color':'#000'});
    }else $('#busyType').css({'color':'#A4A4A4'});
    $('#busySelectButton').removeClass('active');
    $('#addForm .input.select#busyType .cat-wrp').slideUp();
});

$('#region').click(function () {
    $('#regionSelectionButton').removeClass('active');
    $('#addForm .input.select#region .cat-wrp').slideDown();
});

$('#region .option').click(function (e) {
    e.stopPropagation();
    var region = $(this).find('.title').html();
    var id = $(this).find('span').html();
    $('#region .selected').empty();
    $('#region .selected').append(region + '<span class="hidden">'+ id + '</span>');
    if(id != -1){
        $('#region').css({'color':'#000'});
    }else $('#region').css({'color':'#A4A4A4'});
    $('#regionSelectionButton').removeClass('click');
    $('#addForm .input.select#region .cat-wrp').slideUp();
});

$('input[type=file]').change(function (event) {
   if(!$(this).val()){
       $('.img.uploaded').slideUp();
       $('.file-wrp').prev().css({'color':'#e61e1e'});
       $('.file-wrp button.uploadFile').css({'border':'0.266vw solid #e61e1e'});
   }
   else {
       $('.file-wrp').prev().css({'color':'white'});
       $('.file-wrp button.uploadFile').css({'border':'transparent'});
       var filename = $(this).val();
       var ext = filename.split('.');
       ext = ext[ext.length - 1];
       if(!/^jpg$/i.test(ext) && !/^png$/i.test(ext) && !/^jpeg$/i.test(ext)){
           $('.img.uploaded').slideUp();
           $('.file-wrp').next().css({'color':'#e61e1e'});
       }
       else{
           $('.file-wrp').next().css({'color':'white'});
           var formFile = new FormData();
           formFile.append('img', $(this).prop('files')[0]);
           $.ajax({
               url:'/api/temp-upload',
               method:'post',
               data:formFile,
               type:'POST',
               processData:false,
               contentType:false,
               success:function (data) {
                   data = JSON.parse(JSON.stringify(data));
                   if(data.status === 'ok'){
                       $('.img.uploaded').attr('src', data.msg);
                       $('.img.uploaded').slideDown();
                   }
                   else{
                       if(data.msg === 'not image'){
                           $('.file-wrp').next().css({'color':'#e61e1e'});
                       }
                       else if(data.msg === 'oversize'){
                           $('.file-wrp').next().css({'color':'#e61e1e'});
                       }
                       else{
                           alert('Ошибка загрузки, попробуйте заново!');
                           $('.file-wrp').prev().css({'color':'#e61e1e'});
                           $('.file-wrp button.uploadFile').css({'border':'0.266vw solid #e61e1e'});
                       }
                   }

               }
           })
       }
   }
});

form.submit(function (e) {
    e.preventDefault();
    var formdata = new FormData(form[0]);
    var catID = $('#category .selected span').html();
    var exp = $('#exp .selected span').html();
    var busyType = $('#busyType .selected span').html();
    var region = $('#region .selected span').html();
    var prettyDecrp = prettyDescription($('#description').val());
    var prettyDuties = prettyDescription($('#duties').val());
    var prettyCond = prettyDescription($('#conditions').val());


    formdata.append('category', catID);
    formdata.append('exp', exp);
    formdata.append('busyType', busyType);
    formdata.append('region', region);
    formdata.append('descp', prettyDecrp);
    formdata.append('prettyDuties', prettyDuties);
    formdata.append('prettyCond', prettyCond);

    $('.label').css({'color':'white'});
    $('.lilLabel').css({'color':'white'});

    $.ajax({
        url:'/api/publish',
        method:'post',
        data:formdata,
        type:'POST',
        processData: false,
        contentType: false,
        success:function(data) {
            data = JSON.parse(JSON.stringify(data));
            if(data.status === 'ok'){
                // form.children().hide();
                $('#contactModal').modal();
            }
            else{
                if(data.msg === 'final save error' || data.msg === 'save error'){
                    alert('Ошибка сохранения! Попробуйте заново!');
                    window.location.reload();
                }
                else if(data.msg === 'upload error'){
                    alert('Ошибка загрузки картинки! Попробуйте снова или загрузите другую картинку!')
                }
                else{
                    $(data.msg).each(function (index, item) {
                        if(item.field === 'tags'){
                            $('#tags').next('.lilLabel').css({'color':'#e61e1e'});
                        }
                        $('#'+item.field).prev('.label').css({'color':'#e61e1e'});
                    });
                }
            }
        },
        error:function(error) {
            console.error(error);
        }
    });
});

function prettyDescription(desc) {
    return desc.replace(/\r?\n/g, '<br />');
}


var limit = 10;

globalSearch();

$('#category').click(function () {
    $('#categorySelectButton').addClass('active');
    $('.input.select#category .cat-wrp').slideDown();
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
    $('.input.select#category .cat-wrp').slideUp();
});

$('input[name=search]').on('input', function () {

});

$('.checkboxBlock .item').click(function () {
    $(this).toggleClass('active');
    if($(this).hasClass('active')){
        $(this).find('img').attr('src','/uploads/site/icons/checkboxChecked.svg');
    }else $(this).find('img').attr('src','/uploads/site/icons/checkbox.svg');
});

$('.extWrpOpener').click(function () {
    $('.extended-wrp').slideToggle();
    $('.extended-wrp').toggleClass('active');
    if($('.extended-wrp').hasClass('active')){
        $(this).html('Закрыть расширенный поиск');
    }
    else {
        $('.extended-wrp .checkboxBlock .item').removeClass('active');
        $('.extended-wrp .checkboxBlock .item img').attr('src', '/uploads/site/icons/checkbox.svg');
        $(this).html('Расширенный поиск');
    }
});

$('.btn.findVacancy').click(function () {
    $(this).addClass('active');
    limit = 10;
    globalSearch();
});

$('.btn.showMore').click(function () {
    limit = limit + 5;
    globalSearch();
});

function globalSearch() {
    var searchWordsRaw = $('#search').val();
    var searchWordArray = searchWordsRaw.split(' ');
    var filteredSearchWordArray = [];
    $('p').empty();
    for(var i = 0; i<searchWordArray.length; i++){
        if(searchWordArray[i]!=''){
            filteredSearchWordArray.push(searchWordArray[i]);
        }
    }

    var chosenCatId = $('#category .selected span').html();

    var busyTypeArray = [];
    $('.busyTypeBlock .item').each(function (index, item) {
        if($(item).hasClass('active')){
            busyTypeArray.push($(item).find('.value').html());
        }
    });

    console.log(busyTypeArray);

    var tagArray = [];
    $('.tagBlock .item').each(function (index, item) {
        if($(item).hasClass('active')){
            tagArray.push($(item).find('.value').html());
        }
    });

    $.ajax({
        url:'/api/vac-search',
        method:'post',
        type:'POST',
        data:{
          searchWords:filteredSearchWordArray,
            cat:chosenCatId,
            busyType:busyTypeArray,
            tags:tagArray,
            limit: limit

        },
        success:function(data){
            $('.btn.findVacancy').removeClass('active');
            data = JSON.parse(JSON.stringify(data));
            $('.vacancies-wrp h3 span').html($(data).length);
            $('.vacancies-wrp .vacancy-results').empty();
            if($(data).length > 0){
                $('.btn.showMore').fadeIn();
                $(data).each(function (index, item) {
                    $('.vacancies-wrp .vacancy-results').append(
                        '<a href="/vacancy/item?id='+item.id+'">'+
                        '<div class="result-item">' +
                            '<div class="hidden id">' + item.id + '</div>' +
                            '<div class="header-busy-type">' +
                                '<span>'+ item.region +'</span>' +
                                '<img class="img" src="/uploads/site/icons/strelka.svg">' +
                                '<span>' + item.time + '</span>' +
                            '</div>' +
                            '<h2>' + item.cat +' в компанию '+item.company + '</h2>' +
                            '<div class="date">' + prettyDate(item.date) + '</div>' +
                            '<p>' + prettyDescription(item.description) + '</p>' +
                            '<div class="price">от ' + prettyPrice(item.minwage) + '</div>' +
                        '</div></a>'
                    );
                });
            }else{
                $('.vacancies-wrp .vacancy-results').append('<p style="text-align: center; width: 100%">' +
                    'Упс! Подходящих вакансий нет!' +
                    '</p>');
                $('.btn.showMore').fadeOut();
            }
        },
        error:function (error) {
            console.log(error);
        }
    });
}



function prettyDate(date) {
    var prettyDateArray = date.split('-');
    var day = prettyDateArray[2];
    var monthword;
    var year = prettyDateArray[0];
    switch (prettyDateArray[1]){
        case 1:
            monthword = 'Января';
            break;
        case 2:
            monthword = 'Февраля';
            break;
        case 3:
            monthword = 'Марта';
            break;
        case 4:
            monthword = 'Апреля';
            break;
        case 5:
            monthword = 'Майа';
            break;
        case 6:
            monthword = 'Июня';
            break;
        case 7:
            monthword = 'Июля';
            break;
        case 8:
            monthword = 'Августа';
            break;
        case 9:
            monthword = 'Сентября';
            break;
        case 10:
            monthword = 'Октября';
            break;
        case 11:
            monthword = 'Ноября';
            break;
        default:
            monthword = 'Декабря';
            break;
    }
    return day + ' ' + monthword + ' ' + year;
}

function prettyPrice(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + ' UZS';
}

function prettyDescription(desc) {
    return desc.replace('/(\r\n|\n|\r)/gm', '&#13;&#10;');
}
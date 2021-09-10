{
    //---Initialize GET seach
    var searchParams = new URLSearchParams(window.location.search);
}

//Datatable dynamic filter
{
    var dt_filter = {};
    var filter_par = searchParams.get('filter');
    if (filter_par !== null) {
        try {
            dt_filter = JSON.parse(filter_par);
        } catch (e) {
            window.history.pushState(null, null, '.');
        }
    }

    //Add id for authors view page
    if (window.location.pathname.includes('/authors/view'))
        dt_filter.authors = [searchParams.get('id')];
}

//Mouse click on table rows
function InitMouseClick(dataTable, idColNo, tolink) {
    dataTable.on('click', 'td', function() {
        var rowNo = dataTable.row($(this).closest('tr')).index();

        if (typeof(rowNo) != "undefined" &&
            $(this).find("img").length == 0 &&
            $(this).find("input:button").length == 0 &&
            $(this).find("button").length == 0 &&
            $(this).find("select").length == 0) {
            var id = dataTable.cell(rowNo, idColNo).data();
            window.location.href = SITE_URL + tolink + id;
        }
    });


    dataTable.on('mousedown', 'tr', function(e) {
        var rowNo = dataTable.row($(this).closest('tr')).index();
        if (typeof(rowNo) != "undefined") {
            var id = dataTable.cell(rowNo, idColNo).data();
            if (e.which == 2) {
                window.open(SITE_URL + tolink + id);
            }
        }
    });
}

//Mouse select on table rows
function InitMouseSelect(dataTable) {
    dataTable.on('click', 'td', function() {
        var rowNo = dataTable.row(this).index();
        if (typeof(rowNo) != "undefined") {
            dataTable.rows().every(function() {
                $(this.node()).removeClass('selected');
            });
            $(this).closest('tr').addClass('selected');
        }
    });

    dataTable.on('mouseenter', 'tbody tr', function() {
        if (!$(this).hasClass('chapter') && !$(this).hasClass('subchapter'))
            $(this).addClass('hovered');
    });
}

//Contract fullname
function contractName(str) {
    var result = '';
    str.split(' ').forEach(function(el) {
        if (result === '') result = el + ' ';
        else result = result + el[0] + '.';
    });
    return result.trim();
}

//Check string localization
function isEnglish(str) {
    return /^[^а-яА-я]*$/.test(str);
}

//Check if string contains russian chars
function containsRussianChars(str) {
    return /[а-яА-я]+$/.test(str);
}

//Check if string contains more russian chars than english
function strIsMostlyRus(str) {
    var rus = str.match(/[а-яА-ЯёЁ ]+/g);
    var eng = str.match(/[a-zA-Z ]+/g);
    var maxrus = 0;
    if (rus !== null) {
        rus.forEach(one => {
            if (one.length > maxrus) maxrus = one.length;
        });
    }
    var maxeng = 0;
    if (eng !== null) {
        eng.forEach(one => {
            if (one.length > maxeng) maxeng = one.length;
        });
    }
    return maxrus > maxeng;
}

//Revert date from yyyy-mm-dd to dd.mm.yyyy and back
function revertDate(date) {
    if (date == null) return '';

    if (date[4] === '-')
        return date.substr(8, 2) + '.' + date.substr(5, 2) + '.' + date.substr(0, 4);
    else
        return date.substr(6, 4) + '.' + date.substr(3, 2) + '.' + date.substr(0, 2);
}

//Revert date from yyyy-mm-dd h:m:s to h:m:s dd.mm.yyyy and back
function revertDateTime(datetime) {
    if (datetime == null) return '';

    if (datetime[4] === '-')
        return datetime.substr(11) + ' ' + revertDate(datetime);
    else
        return revertDate(datetime.substr(6)) + ' ' + date.substr(0, 5);
}

//Change keyboard layout
function changeKeyboardLayout(str) {
    var str_rus = [
        "й", "ц", "у", "к", "е", "н", "г", "ш", "щ", "з", "х", "ъ",
        "ф", "ы", "в", "а", "п", "р", "о", "л", "д", "ж", "э",
        "я", "ч", "с", "м", "и", "т", "ь", "б", "ю", "ё",
        "Й", "Ц", "У", "К", "Е", "Н", "Г", "Ш", "Щ", "З", "Х", "Ъ",
        "Ф", "Ы", "В", "А", "П", "Р", "О", "Л", "Д", "Ж", "Э",
        "Я", "Ч", "С", "М", "И", "Т", "Ь", "Б", "Ю", "Ё"
    ];
    var str_eng = [
        "q", "w", "e", "r", "t", "y", "u", "i", "o", "p", "[", "]",
        "a", "s", "d", "f", "g", "h", "j", "k", "l", ";", "'",
        "z", "x", "c", "v", "b", "n", "m", ",", ".", "`",
        "Q", "W", "E", "R", "T", "Y", "U", "I", "O", "P", "[", "]",
        "A", "S", "D", "F", "G", "H", "J", "K", "L", ";", "'",
        "Z", "X", "C", "V", "B", "N", "M", ",", ".", "~"
    ];

    if (isEnglish(str)) revert = str.replace(/[a-zA-Z\[\];',.`]/g, function(match) { return str_rus[str_eng.indexOf(match)]; });
    else revert = str.replace(/[а-яА-ЯёЁ]/g, function(match) { return str_eng[str_rus.indexOf(match)]; });
    return revert;
}

//Escape regexp string
function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
}

//Collapsible panel
$('.collapser').click(function() {
    $(this).toggleClass('active');
});

//Textarea disable line breaks
$('.nolinebreaks').on('keyup', function() {
    str = $(this).val();
    $(this).val(str.replace(/\n/g, ' '));
});

/*Status message*/

//Close message on button click
$('#status-bar').on('click', '.msg-btn', function() {
    var container = $(this).closest('.msg-container');
    container.addClass('sliding');
    container.slideToggle();
    setTimeout(function() {
        container.remove();
    }, 400);
});

//Show message on top of the screen (array(status, msg))
function AddStatusMsg(data, isautoclose = true, isscroll = true) {
    var timer = "<div class='circle'>" +
        "<div class='circle-half'></div>" +
        "<div class='circle-half circle-half-right'></div>" +
        "</div>";

    var container = $(
        "<div class='msg-container" + ((data[0] === 2) ? ' msg-error' : '') + "'>" +
        "<div class='msg-text'>" + data[1] + "</div>" +
        "<div class='msg-btn'>" +
        "<i class='zmdi zmdi-close zmdi-action msg-btn-icon'></i>" +
        (isautoclose ? timer : '') +
        "</div>" +
        "</div>");

    //Show no more than 3 msgs
    if ($('#status-bar').children().length > 2) {
        $('#status-bar').find('.msg-container:not(.sliding)').eq(0).find('.msg-btn').trigger('click');
    }

    $('#status-bar').append(container);

    //Scroll to top
    if (isscroll) $('html, body').animate({ scrollTop: 0 }, 500);

    if (isautoclose) {
        setTimeout(function() {
            container.find('.msg-btn').trigger('click');
        }, 10000);
    }
}

//Update last message or add new
function UpdateLastStatusMsg(data, isautoclose = true, isscroll = false) {
    if ($('#status-bar').children().length > 0) {
        $('#status-bar').find('.msg-container').last().remove();
    }

    AddStatusMsg(data, isautoclose, isscroll);
}

//Push status message in session
function PushStatusMsgInSession(data) {
    let msgs = PopStatusMsgsFromSession();

    if (msgs !== null) msgs.push(data);
    else msgs = [data];

    localStorage.setItem('status-msg', JSON.stringify(msgs));
}

//Pop all status messages from session
function PopStatusMsgsFromSession() {
    let data = localStorage.getItem('status-msg');
    localStorage.removeItem('status-msg');

    return JSON.parse(data);
}

//Display previously saved messages
{
    let msgs = PopStatusMsgsFromSession();
    if (msgs !== null) msgs.forEach(a => AddStatusMsg(a, true, false));
}

//*****Nav panel*****
//Activate on click
$('.nav-link').click(function() {
    $('.nav-link').each(function() {
        $(this).removeClass("active");
    });
    $(this).addClass("active");
});

//*****Others*****
//Redirect to the page with passed data
function RedirectWithData(method, href, name, data, target = '') {
    var form = document.createElement('form');
    form.setAttribute('method', method);
    form.setAttribute('action', href);
    form.setAttribute('target', target);

    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = JSON.stringify(data);

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}

//Smooth scrolling

//Handle smooth scroll
$('.smooth-scroll').on('mousewheel', function(e) {
    handlescroll(e.originalEvent, this, $(this).hasClass('min-scroll'));
})

//Perform smooth scroll 
function handlescroll(event, elm, min) {
    var delta = 0;
    var step = 120;
    if (min) step = 360;
    if (event.wheelDelta) delta = event.wheelDelta / step;
    else if (event.detail) delta = -event.detail / 3;

    $(elm).stop().animate({
        scrollTop: $(elm).scrollTop() - (100 * delta)
    }, 500);

    if (event.preventDefault) event.preventDefault();
    event.returnValue = false;
}

//Periodically check logged_in status and open login page on session closed
function checkLoggedIn() {
    $.post(ADMIN_URL, { action: 'is_user_logged_in' }, function(response) {
        if (!response) {
            window.location.href = SITE_URL + '/wp-login.php?redirect_to=' + window.location.href;
        }
    });

    setTimeout(function() { checkLoggedIn() }, 60 * 1000);
}

checkLoggedIn();


//*****Common controls
var imgok = "<i class='zmdi zmdi-check-circle pos-icon'></i>";
var imgno = "<i class='zmdi zmdi-circle-o'></i>";

var file = "<i class='zmdi zmdi-file-text zmdi-action open-action pdf-icon' title='Открыть файл'></i>";
var nofile = "<i class='zmdi zmdi-upload upload-action zmdi-action' title='Помоги товарищам - загрузи файл (:'></i>";

var good = "<i class='zmdi zmdi-thumb-up pos-icon' title='Информация присутствует'></i>";
var bad = "<i class='zmdi zmdi-thumb-down upload-action zmdi-action neg-icon' title='Помоги товарищам - добавь инфу (:'></i>";
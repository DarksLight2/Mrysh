let active;

function pagination_messages(data, count_messages_on_page) {
    let count_pages = Math.ceil(data.length / count_messages_on_page);
    let pagination_box = $("#box_forum_chat_pgn");

    if(pagination_box.empty()) {
        for (let i = 1; i <= count_pages; i++) {
            pagination_box.append($('<a class="pag">'+i+'</a>'));
        }
    }

    let items = $(".pag");

    showPage(items[0], data, count_messages_on_page);

    for(let item of items) {
        item.addEventListener('click', function () {
            showPage(this, data, count_messages_on_page)
        })
    }
}

function showPage(item, data, count_messages_on_page){

    let messages_box = $("#post_message_box");
    let unread_box = $("#mail_unread");

    if(active) {
        active.classList.replace('active', 'pag');
    }

    item.classList.replace('pag','active');

    let pageNumber = +item.innerHTML;
    let start = (pageNumber - 1) * count_messages_on_page;
    let end = start + count_messages_on_page;
    let messages = data.slice(start, end);

    messages_box.empty();
    unread_box.empty();

    for(let message of messages) {

        if(message.unread === 1) {
            unread_box.append('<div class="mail_unread"><div id="block" class="bdr '+message.style+'">' +
                '<div class="wr1"' +
                '<div class="wr2">' +
                '<div class="wr3">' +
                '<div class="wr4">' +
                '<div class="wr5">' +
                '<div class="wr6">' +
                '<div class="wr7">' +
                '<div class="wr8">\n' +
                '                <div class="ml10 mt5 mb2 mr10 sh">' +
                '<img class="icon" src="http://144.76.127.94/view/image/icons/hero_'+message.senderStatus+'_'+message.senderGender+'.png">\n' +
                '\t<a href="view_profile?player_id='+message.sender+'" class="tdn lwhite">'+message.senderLogin+'</a>\n' +
                '\t<span id="date" class="grey1 small fr mail_unread_over">'+message.date+'</span>\n' +
                '\t</div>\n' +
                '\t<div class="ml10 mt2 mb5 mr10 sh">\n' +
                '\t<span class="lyell wwr">'+message.message+'</span>\n' +
                '\t\n' +
                '\t</div>                </div></div></div></div></div></div></div></div></div></div>')
        } else {
            messages_box.append('<div id="block" class="bdr '+message.style+'">' +
                '<div class="wr1"' +
                '<div class="wr2">' +
                '<div class="wr3">' +
                '<div class="wr4">' +
                '<div class="wr5">' +
                '<div class="wr6">' +
                '<div class="wr7">' +
                '<div class="wr8">\n' +
                '                <div class="ml10 mt5 mb2 mr10 sh">' +
                '<img class="icon" src="http://144.76.127.94/view/image/icons/hero_'+message.senderStatus+'_'+message.senderGender+'.png">\n' +
                '\t<a href="view_profile?player_id='+message.sender+'" class="tdn lwhite">'+message.senderLogin+'</a>\n' +
                '\t<span id="date" class="grey1 small fr">'+message.date+'</span>\n' +
                '\t</div>\n' +
                '\t<div class="ml10 mt2 mb5 mr10 sh">\n' +
                '\t<span class="lyell wwr">'+message.message+'</span>\n' +
                '\t\n' +
                '\t</div>                </div></div></div></div></div></div></div></div></div>')
        }
    }

    active = item;
}
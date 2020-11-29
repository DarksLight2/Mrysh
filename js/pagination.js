let active_page = 1;
let type_pagination = null;
let count_items_pagination = null;

function change_page(number_page) {
    active_page = number_page;

    get_chat_messages(type_pagination, count_items_pagination)
}

function show_navigation(count_pages) {

    let navigation_box = $("#navigation_box");

    if(navigation_box.empty()) {
        for (let i = 1; i <= count_pages; i++) {
            if(active_page === i) {
                navigation_box.append($('<span id="active_page" class="pag">'+i+'</span>'));
            } else {
                navigation_box.append($('<a class="pag" onclick="change_page('+i+')">'+i+'</a>'));
            }

            if((i + 1) <= count_pages) {
                navigation_box.append(' | ');
            }
        }
    }
}

function show_items(data, count_messages_on_page, template) {

    let messages_box = $("#messages_box");
    let current_page = $("#active_page").text();
    let start = (current_page - 1) * count_messages_on_page;
    let end = start + count_messages_on_page;
    let items = data.data.slice(start, end);

    messages_box.empty();

    if(template === 'mail') {
        messages_box.append('');
    } else if (template === 'chat'){

        for (let chat_data of items) {

            let style = '';
            let _class = 'lblue';

            if(chat_data.to_user > 0) {
                style = 'font-weight: bold;';
            }

            if(chat_data.to_user === user_id) {
                style = 'color: #eacc54;font-weight: bold;';
                _class = '';

            }


            messages_box.append('' +
                '<div class="mb5">' +
                '<img class="icon" src="http://144.76.127.94/view/image/icons/hero_'+chat_data.sender_activity+'_'+chat_data.sender_gender+'.png">' +
                '<a href="/view_profile?player_id='+chat_data.user_id+'" class="tdn lwhite">'+chat_data.sender_login+'</a>' +
                '<span class="lblue"> (<a class="lwhite" href="#" onclick="send_to_user('+chat_data.user_id+', \''+chat_data.sender_login+'\')">»</a>)</span>: ' +
                '<span class="'+_class+'" style="'+style+'">'+chat_data.message+'</span>  ' +
                '<span style="display: inline"></span>' +
                '</div>');
        }
    }


}
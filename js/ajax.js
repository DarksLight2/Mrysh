function getTimeLeft(where) {
    $.ajax({
        url: '/app/handlesAjax/getTimeLeft.php',
        type: 'POST',
        data: {
            where: where
        },
        success: function (data){

            if (data == 'end')
            {
                //location.reload();
            }
            else
            {
                $("#cooldown_"+where).text(data);
            }
        },
        beforeSend: function (){

            console.log('Loading...')

        }
    })
}

function sendMessage() {
    $.ajax({
        url: '/post_send',
        type: 'POST',
        data: {
            action: 'post_message',
            data:   {
                message: $('#sml').val(),
                recipient: $('#recipient').val(),
                dialog_id: $('#dialog_id').val()
            }
        },
        success: function (response){

            $('#sml').val('');

            let data = JSON.parse(response);

            if(data.message) {
                $('#response').html(show_block(data.message));
            } else {
                $('#response').text('');
            }

            getMessages(data.dialog_id, data.recipient);
        }
    })
}

function getMessages(dialogID, player_id) {
    $.ajax({
        url: '/app/handlesAjax/get_messages.php',
        type: 'POST',
        data: {action: 'getMessages', dialogID: dialogID, player_id: player_id},
        success: function (data){

            let result = $.parseJSON(data);

            pagination_messages(result, 10)
        }
    })
}

function add_new_message(action) {

    let button = $("#submit_message");
    let action_form = $("#action").val();

    button.click(function (){
        if(action === action_form) {
            $.ajax({
                url: '/app/handlesAjax/create_messages.php',
                type: 'POST',
                data: {
                    action: action,
                    message: $("#sml").val(),
                    to_user: $("#to_user").val()
                },
                success: function (response) {

                    let result = $.parseJSON(response);

                    $("#sml").val('');
                    $("#to_user").val(0);

                    console.log(result)

                    if(result.status === 'error') {



                        let response_box = $("#response");

                        response_box.css('display', 'block');
                        response_box.text(result.message);

                        setTimeout(function (){
                            response_box.css('display', 'none');
                            response_box.text('');
                        }, 13000);
                    }

                }
            })
        }
    });
}

function check_new_messages () {
    $.ajax({
        url: '/app/handlesAjax/check_new_messages.php',
        type: 'POST',
        data: {action: 'check'},
        success: function (data){

            let result = $.parseJSON(data);

            if(result.action ===  'show') {
                $('#mail_counter').css('display', 'block');
                $('#mail_counter_count').text('1');

                let selector = $('#new_message_index');
                if(selector) {
                    selector.css('display', 'block');
                }
            }
        }
    })
}

function get_chat_messages(type, count_messages_on_page) {
    $.ajax({
        url: '/app/handlesAjax/get_chat_messages.php',
        type: 'POST',
        data: {
            chat_data: {type: type}
        },

        success: function (response){

            if(type_pagination === null)
                type_pagination = type;

            if(count_items_pagination === null)
                count_items_pagination = count_messages_on_page;

            let data = $.parseJSON(response);

            let count_pages = Math.ceil(data.count_rows / count_messages_on_page);

            show_navigation(count_pages);
            show_items(data, count_messages_on_page, 'chat');
        }
    })
}
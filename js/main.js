function send_to_user(user_id, user_login) {

    let box =  $("#sml");
    let to_user = $("#to_user");
    let box_text = user_login+', '+box.val();

    if(to_user.val() > 0) {
        let text = box.val().split(',')[1];
        box_text = user_login+','+text;

    }

    to_user.val(user_id);
    box.val(box_text);
}

let old_title = document.title;

function change_title(text) {

    if(text === null) {
        document.title = old_title;
    } else {

        setInterval(function () {
            document.title = text;

            setTimeout(function () {
                document.title = old_title;
            }, 1000)
        }, 2000)
    }
}
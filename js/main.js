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
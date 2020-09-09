function getTimeLeft(where)
{
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
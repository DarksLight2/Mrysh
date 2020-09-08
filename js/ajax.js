function getTimeLeft()
{
    $.ajax({
        url: '/app/handlesAjax/getTimeLeft.php',
        type: 'POST',
        data: {
            where: 'arena'
        },
        success: function (data){

            if (data == 'end')
            {
                location.reload();
            }
            else
            {
                $("#cooldown_arena").text(data);
            }
        },
        beforeSend: function (){

            console.log('Loading...')

        }
    })
}
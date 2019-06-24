$(document).ready(function(){

    $(document).on('submit', '#postMakerForm', function(e)
    {
        e.preventDefault();

        var text = $("#postText");

        if(text.val() != "")
        {
            $.post('/thought/create', {postText: text.val()}, function(data){
                alert(data)
            });
        }else{

        }

        return false;
    });
});
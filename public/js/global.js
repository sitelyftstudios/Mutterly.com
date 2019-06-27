$(document).ready(function(){
    var busy = false;

    $(document).on('click', '.close-btn', function(){
        location.reload();
    });

    $(document).on('submit', '#postMakerForm', function(e)
    {
        if(busy == false)
        {
            busy = true;

            e.preventDefault();

            var text = $("#postText");
            var number = $("#postNumber");

            if(text.val() != "")
            {
                $.post('/thought/create', {postText: text.val(), postNumber: number.val()}, function(data)
                {
                    if(data.code == 1)
                    {
                        $("#successModal").modal()
                    }else{

                    }
                });
            }else{

            }

            busy = false;
        }

        return false;
    });

    $(document).on('click', '.likeBtn', function(e)
    {
        if(busy == false)
        {
            busy = true;
            e.preventDefault();

            var t = $(this);

            var id = t.data('id');

            if(id != "")
            {
                $.post('/thought/like', {postId: id}, function(data){
                    var obj = jQuery.parseJSON(data);

                    if(obj.code == 1)
                    {
                        $("#icon-" + id).addClass('bounce');
                        $("#icon-" + id).html('<i class="fas fa-heart"></i>');
                        $("#count-" + id).html(obj.like_count);
                    }else{
                        alert(obj.status);
                        return false;
                    }
                });
            }else{

            }

            busy = false;
        }

        return false;
    });
});
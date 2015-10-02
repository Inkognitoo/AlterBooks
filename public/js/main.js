//TODO: протестировать и поправить
$("#follow").submit(function() {
    yaCounter32344465.reachGoal('sendEmail');

    $("#emailButton").attr('disabled', true);
    var url = "/api/v1/follow";

    $.ajax({
        type: "POST",
        url: url,
        data: {
            email: $("#email").val()
        },
        dataType: "json",
        success: function(data)
        {
            console.log(data);
            $("#emailButton").removeAttr('disabled');
            yaCounter32344465.reachGoal('sendEmailSuccess');
        },
        error: function (data) {
            console.log(JSON.parse(data.responseText));
            $("#emailButton").removeAttr('disabled');
            yaCounter32344465.reachGoal('sendEmailFail');
        }
    });

    return false;
});


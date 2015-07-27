$(function()
{
    $('.like').bind('click', function(event)
    {
        var id = this.id;
        var tmp = id.split('_');
        var type = tmp[1];
        var resID = tmp[2];
        var param = {type:type, resID:resID};
        console.log("param", param);

        // 加入最愛
        $.ajax
        ({
            type     : 'get',
            data     : param,
            dataType : 'json',
            url      : '/favorite/add',
            error    : function(){alert('ajax error!');},
            success  : function(data)
            {
                alert('加入最愛成功！');
            }
        });
    })
});

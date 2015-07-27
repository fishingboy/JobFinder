$(function()
{
    $('.sorted_table').sortable(
    {
        containerSelector: 'table',
        itemPath: '> tbody',
        itemSelector: 'tr',
        placeholder: '<tr class="placeholder"/>',
        onDrop: function  ($item, container, _super)
        {
            // 拖拉動畫
            var $clonedItem = $('<li/>').css({height: 0});
            $item.before($clonedItem);
            $clonedItem.animate({'height': $item.height()});

            $item.animate($clonedItem.position(), function()
            {
                $clonedItem.detach();
                _super($item, container);
            });

            // 找出目前的順序
            var id = $item.attr('id');
            var _map = $('.sorted_table tbody').children().map(function()
            {
                return $(this).attr('id');
            });
            var sn = $.inArray(id, _map);

            // 更新排序
            $.ajax
            ({
                type     : 'get',
                data     : {id:id.split('_')[1], sn:sn},
                dataType : 'json',
                url      : '/favorite/sort',
                error    : function(){alert('ajax error!');},
                success  : function(data)
                {
                    // 重整畫面
                    window.location.reload(true);
                }
            });
        }
    });
});

$(function(){


    var isHide = 0;
    $(".btn-ewm").click(function () {
        isHide++
        if(isHide==1){
            $(this).find("img").show()
        }else{
            $(this).find("img").hide()
            isHide=0
        }


    })






    // 页数
    var page = 0;
    // 每页展示5个
    var size = 5;
    var activeText;
    var activeClass;
    // dropload
    $('.content').dropload({
        scrollArea : window,
        loadDownFn : function(me){
            page++;
            // 拼接HTML
            var result = '';
            $.ajax({
                type: 'GET',
                url: 'src/json/update.json?page='+page+'&size='+size,
                dataType: 'json',
                success: function(data){
                   console.log(data.lists)
                    var arrLen = data.lists.length;
                    console.log(arrLen)
                    if(arrLen > 0){
                        for(var i=0; i<arrLen; i++){
                            if(data.lists[i].active == 0){
                                activeText="通过";
                                activeClass = "color-blur"
                            }else if(data.lists[i].active==1){
                                activeText="审核未通过";
                                activeClass = "color-red"
                            }else if(data.lists[i].active==2){
                                activeText="审核中";
                                activeClass = "color-yellow";
                            }
                            result += '<li class="item opacity">'
                            result +='<span>'+ data.lists[i].date + '</span>'
                            result +='<span>'+ data.lists[i].gold + '</span>'
                            result +="<span class='last "+activeClass+"'>"+ activeText + "</span>"
                            result +='</li>';
                        }
                        // 如果没有数据
                    }else{
                        // 锁定
                        me.lock();
                        // 无数据
                        me.noData();
                    }
                    // 为了测试，延迟1秒加载
                    setTimeout(function(){
                        // 插入数据到页面，放到最后面
                        console.log(result)
                        $('.lists ul').append(result);
                        // 每次数据插入，必须重置
                        me.resetload();
                    },1000);
                },
                error: function(xhr, type){
                    console.log('Ajax error!');
                    // 即使加载出错，也得重置
                    //me.resetload();
                }
            });
        }
    });
});
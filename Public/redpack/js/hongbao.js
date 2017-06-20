window.onload=function(){
    //禁止浏览器默认事件和冒泡事件
    //document.addEventListener("touchmove",function(e){
    //    e.preventDefault();
    //    e.stopPropagation();
    //},false);
    //单独点击红包
    //时间的全局变量
    var timeId;
    var allArray = [];
    // var member_coins;
    // var coin_configs;
    //游戏状态全局变量,0表示未开始，1表示开始，2表示结束；
    var state=0;
//	屏幕宽度赋值给con的盒子,获取屏幕宽度，让每一个列的宽等于高。
    var b=4;
    var rowhei=window.innerWidth/b;
//屏幕高度的与四分之一的宽度的倍数
    var bb=parseInt(window.innerHeight/(rowhei));

//点击变白；
    init();
//循环执行crow，初始化界面。
    function init(){
        for(var i=0;i<bb;i++){
            crow();
        }

        dianji();
    }

//事件委托
    function dianji(){
        var con = document.getElementById("container");
        var aLi = con.getElementsByTagName("div");
        con.onclick = function(ev){
            var ev = ev || window.event;
            var target = ev.target || ev.srcElement;
            if(target.nodeName.toLowerCase() == "div"){
                var that=target;
                var index;
                var a=bb*5-4;
                for(var i=0;i<aLi.length;i++)if(aLi[i]===target)index=i;
                    //判断下标
                    if(index>=a&&target.className.indexOf('red')!==-1){
                        target.className='cell';
                        move();
                        score();
                    }else
                    //  {
                    //
                    //     target.className = 'cell black';
                    //     red_count(document.getElementById('score').innerHTML);
                    //     delay();
                    //     stop();
                    // }

                    {
                               if(num==0){
                                   console.log("成功了");
                                   target.className = 'cell black';
                                   first.className='dn';
                                   second.className='';
                                   third.className='dn';
                                   //four.className='dn';
                                   five.className='dn';
                                   six.className='';
                                   bg6.style.display='block';

                               }else {
                                   target.className = 'cell black';
                                     red_count(document.getElementById('score').innerHTML);
                                   delay();
                                   stop();
                               }
                           }




            }

        }

    }

    var timeId;
    var totaltime = 20;
    //		后两位:满十就加一
    var totalSec = 20 * 1000;
//	获取倒计时的时间li
    var liArr = document.querySelectorAll('#daojishi li');
//倒计时：
    function cutDownTime(type) {
        timeId = setInterval(function () {
            if (type <= 0) {
                clearInterval(timeId);
                //时间到了，执行改变字体函数，改变third页
              red_count(document.getElementById('score').innerHTML);
                changeInt();
                delay();
                return;
            }
            type -= 10;
//			当前的秒对应到微妙,毫秒
//			30秒
//			300微妙
//			3000毫秒
            var miao = Math.floor(type / 1000);
            var haomiao = Math.floor(type % 1000 / 10);
            liArr[0].innerHTML = Math.floor(miao / 10);
            liArr[1].innerHTML = miao % 10;
            liArr[3].innerHTML = Math.floor(haomiao / 10);
            liArr[4].innerHTML = haomiao % 10;
        }, 10)
//}
    }
    //时间到以后，改变秒表的innerhtml属性；
    function changeInt(){
        liArr[0].innerHTML='时';
        liArr[1].innerHTML='&nbsp';
        liArr[2].innerHTML='间';
        liArr[3].innerHTML='&nbsp';
        liArr[4].innerHTML='到';
        liArr[5].innerHTML='!';

    }
    //这就是要实现停止的方法
    function stop() {
        clearInterval(timeId);
    }

//计分：
    var	num=0;//初始化红包的总数
    function score(){

    	$('score').innerHTML=parseInt($('score').innerHTML)+1;
        num+=1;
//	当分数为1的时候即说明点开了第一个红包！
        if($('score').innerHTML==1){
            cutDownTime(parseInt(document.getElementById('games_time').value));
        }
       return num;
    }



//移动move,点击一下移动的距离
    function move(){
        //	获取container当前的top值；获取浏览器渲染后的实际值。
        var top;
        var con;
        var timer;
        function test(){
            con=$('container');
            top=parseInt(window.getComputedStyle(con,null)['top']);
        }
        test();
        timer=setInterval(moveDiv,0.01);
        function moveDiv(){
            top+=4;
            con.style.top=top+"px";
            if(top=100){
                crow();
                clearInterval(timer);
                con.style.top='0';
                drow();
            }
        }

    }


    //	创建div，总共是n行4列；即是bb基数；
    function crow(){
        var con=$('container');
        var classes=createSn();
        var row=cdiv('row');
        for(var i=0;i<4;i++){
            row.appendChild(cdiv(classes[i]));
        }
        if(con.firstChild==null){
            con.appendChild(row);
        }
        else {
            con.insertBefore(row,con.firstChild);
        }

    }
    //	创建div.row,包装4个div。cell
    function cdiv(className){
        var div=document.createElement('div');
        div.className=className;
        return div;

    }
    //创建随机数组
    function createSn(){
        var arr=['cell','cell','cell','cell'];
        var i=Math.floor(Math.random()*4);
        arr[i]='cell red';
        return arr;
    }
    //删除最后一行
    function drow(){
        var con =$('container');
        con.removeChild(con.lastChild);
    }

//函数延迟几秒执行
    //显示半透明
    //结束游戏以后把背景透明显示出来，然后
    function delay(){
        setTimeout(function(){
                $('bg').style.display="block";
                third.className='';
                console.log("获取");
            distributions();
        },600);
    }

    //获取各个页面的节点，
    var first=document.getElementById('first');
    var second=document.getElementById('second');
    var third=document.getElementById('third');
    //var four=document.getElementById('four');
    var five=document.getElementById('five');
    var six=document.getElementById('six');
//  开始游戏按钮
    var btnfirst=document.getElementById('firstbtn');
//  一键开启按钮
    var btnthird=document.getElementById('openbtn');
    //继续开启按钮
    var btngoon=$('agin_goon');
//  再玩一次按钮
//    var btnfour1=document.getElementById('againbtn');
//  个人中心按钮
//    var btnfour2=document.getElementById('backbtn');
    var bg1=document.getElementById('bg1');
	var bg5=$('bg5');
    var bg6=$('bg6');
    //游戏结束按钮
    var end1=$('agin_goon2');
    var end2=$('againbtn2');
//点击开始游戏的时候跳入游戏主体
    btnfirst.onclick=function (){
        //点击开始时弹出层，是否充值。
        //雷振兴开始
        var member_coins;
        var coin_configs;
        //扣除玩家金币
        cut_coin_ajax();
        //雷振兴结束
        var member_coins = document.getElementById('member_coin').value;
        var coin_configs = document.getElementById('coin_config').value;
        // games_time   = document.getElementById('games_time').value;
        if(coin_configs == 0){
            layer.msg("该游戏还未配置！",{icon:2});
            return ;
        }
        if(parseInt(member_coins) < parseInt(coin_configs)){
            layer.confirm('您的金币不足，是否充值金币？', {
                btn: ['好的', '取消'], //可以无限个按钮
            }, function(index, layero){
                //按钮【按钮一】的回调
                window.location.href='recharge';
            }, function(index){
                layer.close(index);
            });
            return;
        }
        layer.load(1);
        cut_member_coin()
        setTimeout(function(){
            var games_states = document.getElementById('games_state').value;
                if(games_states == 1){
                    first.className='dn';
                    second.className='';
                }else{
                    layer.msg("游戏数据解析异常1！",{icon:2});
                }
                layer.closeAll('loading');
        },1000);

    }
    //一键开启，点击进入红包墙,隐藏一键开启第三个页面
    btnthird.onclick=function (){
        // layer.load(1);
        //获取所以列表
        // var addHtml;
        // // var getAllBox = num;
        // // var allArray = [];
        // var i = 0;
        box();
        reRun(num);
        third.className='dn';
        five.className='';
        bg5.style.display="block";
        // redWall();
    }
    end1.onclick=function (){

        //扣除金币操作
        var member_coins = document.getElementById('member_coin').value;
        var coin_configs = document.getElementById('coin_config').value;
        if(coin_configs == 0){
            layer.msg("该游戏还未配置！",{icon:2});
            return ;
        }
        if(parseInt(member_coins) < parseInt(coin_configs)){
            layer.confirm('您的金币不足，是否充值金币？', {
                btn: ['好的', '取消'], //可以无限个按钮

            }, function(index, layero){
                //按钮【按钮一】的回调
                window.location.href='recharge';
            }, function(index){
                layer.close(index);
            });
            return;
        }
        layer.load(1);
        cut_member_coin();
        setTimeout(function(){
            var games_states = document.getElementById('games_state').value;
            if(games_states == 1){
/************************/
                //four.className='dn';
                five.className='dn';
                first.className='dn';
                six.className='dn';
                second.className='';
                bg6.style.display="none";
                num=0;
                var  box5= $('box5');
                var childs = box5.childNodes;
                for(var i = childs.length - 1; i >= 0; i--) {
                    box5.removeChild(childs[i]);
                }
                $('container').getElementsByTagName('div').innerHTML = "";
                init();
                liArr[0].innerHTML='2';
                liArr[1].innerHTML='0';
                liArr[2].innerHTML="'";
                liArr[3].innerHTML='0';
                liArr[4].innerHTML='0';
                liArr[5].innerHTML="''";
                $('score').innerHTML = "0";
                /************************/
            }else{
                layer.msg("游戏数据解析异常！",{icon:2});
            }
            layer.closeAll('loading');
        },1000);
    }
    end2.onclick=function (){
        setReload();
    }
//		点击返回主页，隐藏第四页面。跳转第一页面。重新加载
//    btnfour2.onclick=function (){
//        setReload();
//    }
//		刷新页面，重新加载
    function setReload(){
        window.location.reload();
    }


// 红包墙开始
  function redWall(){
  	var box5 = document.getElementById("box5");
    var aLi = box5.getElementsByTagName("div");
    box5.onclick = function(ev){
    var ev = ev || window.event;
    var target = ev.target || ev.srcElement;
         if(target.nodeName.toLowerCase() == "div"){
             var that=target;
             var index;
             for(var i=0;i<aLi.length;i++)if(aLi[i]===target)index=i;
             if(index>=0&&target.className.indexOf('redcell')==0 ){
                 console.log(target.className.indexOf('redcell')+"你好我的下标");
                 //var four=document.getElementById('four');
                     // var index2 = layer.load(1); //换了种风格
                     var b=cdiv("t1");
                     handle_gold(target,b);
                      setTimeout(function(){
                          first.className='dn';
                          second.className='dn';
                          third.className='dn';
                          //four.className='';
                          five.className='';
                          six.className='dn';
                          //bg4.style.display='block';
                          num--;
                          $('hbnum').innerHTML='还剩'+num+'个红包';
                          if(num==0){
                              first.className='dn';
                              second.className='dn';
                              third.className='dn';
                              five.className='';
                              six.className='';
                              bg6.style.display='block';
                          }else{
                              five.className='';
                          }
                          // layer.close(index2);
                      },1000);

                 return num;
             }

         }

   }}
    //点击继续开启,隐藏金币页面four，显示红包墙five.
    btngoon.onclick= function () {
        //金币数显示

        if(num==0){
           six.className='';
            bg6.style.display='block';
            first.className='dn';
            second.className='dn';
            third.className='dn';
            //four.className='dn';
            five.className='';
        }else{
            //four.className='dn';
            five.className='';
    }}
//生成红包并排列下来
    function box(){
        var box5 = document.getElementById("box5");
        for (var i=0;i<num;i++){
                box5.appendChild(cdiv('redcell'));
            $('hbnum').innerHTML='还剩'+num+'个红包';
        }
    }
    //获取元素id
    function $(id){
        return document.getElementById(id);
    }
}
function handle_gold(a,b){
    jQuery(function (){
        $.post("Redpackets/operate",{"s":1,'open_red_pack':1},function (data){
            var val = eval('(' + data + ')');
            if(val.status == 1){//成功
                $("#txt").val(val.price);
                setTimeout(function (){
                   a.className = "yellcell";
                   a.appendChild(b);
                   b.innerHTML=val.price;
                },500);
            }
        });
    });
}
function reRun(num){
    var red_num = num;
    jQuery(function (){
        $.ajax({
          type: 'POST',
          url: 'Redpackets/operate',
          async:false,
          dataType: 'json',
          data: {
            "s":1,
            'open_red_pack':red_num
          },
          success: function(data){
            allArray = data.price;
            showData(allArray.length);
          },
        });
    });
}
function showData(num){
    for(var i=0;i<allArray.length;i++){
        if(!$("#box5 .redcell").eq(i).hasClass("yellcell")){
            //一键开启红包
            $("#box5 .redcell").addClass("yellcell");
            $("#hbnum").text("还剩"+num+"个红包");
        }
        $("#box5 .redcell").eq(i).html("<div style='display:none' class='t1'>"+allArray[i]+"</div>")
        if(i>=allArray-1){
            setTimeout(function(){
                $("#six,#bg6").show();
            },getAllBox*350)
        }
    }
    layer.closeAll();
}
function red_count(num){
  jQuery(function (){
    $.post("Redpackets/count",{"s":1,"num":num});
  });
}
// 雷振兴添加
function cut_coin_ajax(){
    var start_game =0; //开始标示
    jQuery(function (){
        $.ajax({
          type: 'POST',
          url: 'Redpackets/game_start_cut_coin_ajax',
          async:false,
          dataType: 'json',
          data: {
            'sd':1
          },
          success: function(data){
            member_coins =data.member_coin;
            coin_configs = data.coin_config;
            start_game =1;
          },
        });
        return false;
        if (start_game != 1) {
            return false;
        }else{
            return true;
        }
    });
} 
var games_time;   //开始游戏时间
var other_coins; //获得金币数
var user_photo; //用户头像
var user_name;  //用户昵称

var win_w=600;
var win_h=966;
var player,
    backLayer,loadingTip,
    ws,roomID,Game_ready,
    Game_start,Game_over_one,
    Game_over_double,user,Game_bg,
    room,Util,
    Gameing,plane,socket,
    score;

var startLayer,ingLayer,overOneLayer,overDoubleLayer;
var stopGame = false;
    (function(GameInit){
        Laya.init(win_w,win_h,Laya.WebGL);
        // Laya.Stat.show(0,0);
        Laya.stage.alignH=Laya.Stage.ALIGN_CENTER;
        Laya.stage.alignV=Laya.Stage.ALIGN_TOP;
        Laya.stage.scaleMode = 'showall';
        Laya.stage.scaleMode = 'exactfit';
        Laya.stage.screenMode = "none";
        Laya.stage.bgColor = "#000000";
        backLayer = new Laya.Sprite();
        backLayer.width = win_w;
        backLayer.height = win_h;
        backLayer.pos(0,0);
        Laya.stage.addChild(backLayer);
        loadUI();  
    })()

 function loadUI(){
        var imgArray = [
            {url:"/Public/game/plane/res/atlas/comp.json",type:Laya.Loader.ATLAS},
            {url:"/Public/game/plane/res/atlas/bang.json",type:Laya.Loader.ATLAS},
            {url:"/Public/game/plane/res/atlas/fire1.json",type:Laya.Loader.ATLAS},
            {url:"/Public/game/plane/res/atlas/fire2.json",type:Laya.Loader.ATLAS},
            {url:"/Public/game/plane/res/atlas/gameReady.json",type:Laya.Loader.ATLAS},
            {url:"/Public/game/plane/res/atlas/gameStart.json",type:Laya.Loader.ATLAS},
            {url:"/Public/game/plane/res/atlas/gameoverdouble.json",type:Laya.Loader.ATLAS},
            {url:"/Public/game/plane/res/atlas/gameoverone.json",type:Laya.Loader.ATLAS},
            {url:"/Public/game/plane/comp/red_1.png",type:Laya.Loader.IMAGE},
            {url:"/Public/game/plane/comp/red_2.png",type:Laya.Loader.IMAGE},
            {url:"/Public/game/plane/comp/blue_1.png",type:Laya.Loader.IMAGE},
            {url:"/Public/game/plane/comp/blue_2.png",type:Laya.Loader.IMAGE},
        ]
        Laya.loader.load(imgArray,Laya.Handler.create(this,onLoadUI),Laya.Handler.create(this,onProgress,null,false));
        lodaTextTip = new Laya.Text();
        lodaTextTip.text = "正在加载中...";
        lodaTextTip.color = "#ffffff";
        lodaTextTip.fontSize = 30;
        lodaTextTip.pos((win_w-lodaTextTip.width)/2,win_h/2);
        backLayer.addChild(lodaTextTip);
    }
    function onProgress(progress){
        lodaTextTip.text = "";
        lodaTextTip.text = "正在加载中..."+Math.floor(progress*100)+"%";
    }
    function onLoadUI(){
        memberinfo();
        user = {Avatar:user_photo,nickName:user_name};
        util = new Util();
        room = new Room();
        lodaTextTip.removeSelf();
        Game_start = new GameStart();
    }
    
    function memberinfo(){
          $.ajax({
            'url':'/Mobile/Plane/getcoin',
            'dataType':'json',
            'data':{

            },
            'async':false,
            success:function(data){
                if (data.status==1) {  //表示可以开始游戏
                    games_time = data.games_time;
                    other_coins = data.other_coins;//获得金币数
                    user_photo = data.user_photo;
                    user_name = data.nickname;
                    if (user_photo == -1) {
                        user_photo = '/Public/game/plane/comp/touxiang.png';
                    }
                }else if(data.status == 3){ //金币不足
                    game_start = 3;
                    layer.confirm('您的金币不足，是否充值金币？', {
                       btn: ['好的', '取消'], //可以无限个按钮

                    }, function(index, layero){
                       //按钮【按钮一】的回调
                       window.location.href = '';
                    }, function(index){

                    });
                }else{//开始游戏失败
                    game_start = 4;
                    
                }
            }

        });
    
    }
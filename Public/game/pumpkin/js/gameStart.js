var games_time;   //开始游戏时间
var other_coins; //获得金币数
var user_photo; //用户头像
var gameStart=(function(_super){
    function gameStart(){
        gameStart.super(this);
        this.btn_start.on(Laya.Event.MOUSE_DOWN,this,this.startGame);
    }
    Laya.class(gameStart,"gameStart",_super);
    gameStart.prototype.startGame=function(){
        var game_start = 1;
        $.ajax({
            'url':'/Mobile/Pumpkin/getcoin',
            'dataType':'json',
            'data':{

            },
            'async':false,
            success:function(data){
                if (data.status==1) {  //表示可以开始游戏
                    games_time = data.games_time;
                    other_coins = data.other_coins;//获得金币数
                    user_photo = data.user_photo;
                    if (user_photo == -1) {
                        user_photo = '/Public/game/pumpkin/comp/touxiang.png';
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

        })
        if (game_start != 1) {
            return false;
        }
        SoundManges=new SoundManage();
        SoundManges.SoundPlay("bgMusic"); 
        player.addChild(new Pumpkin());
        this.removeSelf();
    };
    return gameStart;
})(ui.GamestartUI);



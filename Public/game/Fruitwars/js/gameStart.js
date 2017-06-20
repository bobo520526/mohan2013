var scoreNum; //初始化分数
// 游戏时间
var hour;
var min;
var second;
// 游戏时间结束

// 子弹扣除金币
var cut_score_one;
var cut_score_two;
var cut_score_three;
// 子弹扣除金币结束

// 打中获得除金币
var get_score_one;
var get_score_two;
var get_score_three;
// 打中获得金币结束
var gameStart=(function(_super){
    function gameStart(){
        gameStart.super(this);
        this.btn_start.on(Laya.Event.MOUSE_DOWN,this,this.startGame);
    }
    Laya.class(gameStart,"gameStart",_super);
    gameStart.prototype.startGame=function(){
        var start_game=1;
        //开始扣除金币
        $.ajax({
            'url':'/Mobile/Fruitwars/getcoin',
            'dataType':'json',
            'data':{

            },
            'async':false,
            success:function(data){
                if (data.status==1) {  //表示可以开始游戏
                    scoreNum = data.scoreNum; //赋值用户金币量
                    hour = data.hour;
                    min = data.min;
                    second = data.secend;
                    // 子弹扣除金币
                    cut_score_one= data.cut_score_one;
                    cut_score_two=data.cut_score_two;
                    cut_score_three=data.cut_score_three;
                    // 打中获得除金币
                    get_score_one  = data.get_score_one;
                    get_score_two  = data.get_score_two;
                    get_score_three= data.get_score_three;
                }else if(data.status == 3){ 
                    start_game = 3;
                    layer.confirm('您的金币不足，是否充值金币？', {
                       btn: ['好的', '取消'], //可以无限个按钮

                    }, function(index, layero){
                       //按钮【按钮一】的回调
                       window.location.href = '';
                    }, function(index){

                    });
                    
                }else{  //开始游戏失败
                    // return false;
                    start_game = 4;
                }
            }

        })
        if (start_game!=1) {
            return false;
        }
        //游戏开始扣除金币结束
        SoundManges.SoundPlay("bgMusic"); 
        this.backLayers = new Laya.Sprite();
        player.addChild(this.backLayers)
        this.game=new Vegetables();
        this.backLayers.addChild(this.game);
    };
    return gameStart;
})(ui.gameStartUI);
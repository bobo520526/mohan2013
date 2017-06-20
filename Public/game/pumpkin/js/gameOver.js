// var gameOver=(function(_super){
//     function gameOver(_super){
//         gameOver.super(this);
//         this.popup()
//         this.btn_again.on(Laya.Event.MOUSE_DOWN,this,this.Again);
//         this.btn_person.on(Laya.Event.MOUSE_DOWN,this,this.Person);
//     };
//     Laya.class(gameOver,"gameOver",_super);
//     var _proto=gameOver.prototype;
//     // 再玩一次
//     _proto.Again=function(){
//         // this.removeSelf();
//         player.addChild(new Pumpkin());
//     }
//     // 返回游戏中心
//     _proto.Person=function(){

//     }

//     return gameOver;
// })(ui.gameOverlUI);

(function(){
    function gameOver(){
        // $.ajax({
        //     'url':'Pumpkin/operate',
        //     'dataType':'json',
        //     'type':'post',
        //     'async':false,
        //     'data':{
        //         'coin':score
        //     },
        //     'async':false,
        //     success:function(data){
        //         console.log(data);
        //         if (data.status==1) {
    
        //         }else{
                    
        //         }
        //     }

        // })
        gameOver.__super.call(this);
        this.init()
    }
    Laya.class(gameOver,"gameOver",laya.display.Sprite);
    var _proto=gameOver.prototype;
    _proto.init = function(){
        $.ajax({
            'url':'/Mobile/Pumpkin/operate',
            'dataType':'json',
            'type':'post',
            'async':false,
            'data':{
                'coin':score
            },
            'async':false,
            success:function(data){
                if (data.status==1) {
    
                }else{
                    
                }
            }

        })
        this.go = new gameOverlUI()
        this.go.popup();
        this.go.btn_again.on(Laya.Event.MOUSE_DOWN,this,this.Again);
        this.go.btn_person.on(Laya.Event.MOUSE_DOWN,this,this.Person);
        this.scoreShow(score);
    }
    // 再玩一次
    _proto.Again=function(){
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
        this.go.close();
        player.addChild(new Pumpkin());
    }
    // 返回游戏中心
    _proto.Person=function(){
        window.location.href = '/Mobile/Index/gamelist';

    }
    _proto.scoreShow=function(num){
       var s=this;
		s.go.s1.index = Math.floor(num/1000);
		s.go.s2.index = Math.floor(num%1000/100);
		s.go.s3.index = Math.floor(num%1000%100/10);
		s.go.s4.index = Math.floor(num%1000%100%10);
		
	}
    
})()
// 主舞台，实例化homeui
var score;
(function(){
    
    function Pumpkin(){
    Pumpkin.__super.call(this);
    this.init();
    this.touXiang();
 
}
    Laya.class(Pumpkin, "Pumpkin", laya.display.Sprite);

    var _proto = Pumpkin.prototype;
    _proto.init=function(){
        this.home=new homeUI();
        this.addChild(this.home);
        // 实例南瓜
        this.apeLayer=new aPe();
        this.addChild(this.apeLayer);

        // 实例女巫
        this.witLayer=new Witch();
        this.addChild(this.witLayer);
        Laya.timer.frameLoop(1,this,this.onLoop);
        this.home.musicplay.width=70;
        this.home.musicplay.height=70;
        this.home.musicplay.on(Laya.Event.MOUSE_DOWN,this,this.controlMusic);
        this.home.musicstop.on(Laya.Event.MOUSE_DOWN,this,this.controlMusic);
        this.state="open";
        //初始化时间
        this.countDown(games_time);
        
        // 初始化分数
        score=0;
        this.home.score.text=score;
        this.num=0;
    }
    _proto.onLoop=function(){
        for(key in this.apeLayer.pkLayer._childs){
              if(this.apeLayer.pkLayer._childs[key].mode=="die"){
                //   女巫出现
                this.witLayer.witAppear(-10,this.apeLayer.pkLayer._childs[key].y);
                }
              if(this.apeLayer.pkLayer._childs[key].types=="win" && this.apeLayer.pkLayer._childs[key].num == 1){
                this.addScoreText(this.apeLayer.pkLayer._childs[key].x,this.apeLayer.pkLayer._childs[key].y);
                this.Facelift(this.apeLayer.pkLayer._childs[key],this.apeLayer.pkLayer._childs[key].x,this.apeLayer.pkLayer._childs[key].y);
                // 计分
                this.home.score.text=parseInt(score)+10;
                score= this.home.score.text;
                }
        }
    }
   
     _proto.controlMusic=function(){
         if(this.home.musicplay.visible){
            this.home.musicplay.visible=false;
            this.home.musicstop.visible=true;
            this.state="close";
            SoundManges.StopPlay("bgMusic"); 
         }
         else if(this.home.musicstop.visible){
            this.home.musicplay.visible=true;
            this.home.musicstop.visible=false; 
            this.state="open";
            SoundManges.SoundPlay("bgMusic"); 
         }
    }
    //加载南瓜图
   _proto.Facelift=function(s_name,x,y){
        this.pukplay=new Laya.Sprite();
        this.apeLayer.addChild(this.pukplay);
        this.loadImage("comp/puk.png");
        this.pukplay.pos(x,y);
        s_name.removeSelf();
    }
 // 文字效果
  _proto.addScoreText =function (x,y){
        var s=this;
        // 播放音效
        if(s.state=="open"){
            SoundManges.SoundPlay("hitSound"); 
        }
        else if(s.state=="close"){
            SoundManges.StopPlay("hitSound"); 
        }
        s.p1 = new Laya.Text();
        s.p1.fontSize = 25;
        s.p1.color = "#ff0000";
        s.p1.x = x;
        s.p1.y = y;
        s.p1.text = "+"+parseInt(other_coins);
        s.p1.bold = true;
        s.p1.width = 200;
        s.p1.height = 200;
        s.apeLayer.addChild(s.p1);
        Laya.Tween.to(s.p1, {y: y - 120,alpha: 0}, 2000, null, Laya.Handler.create(s.p1, function() {
            s.p1.removeSelf();
        }));
   }
     //倒计时
            _proto.countDown = function(type){
            var s=this;
           timeId = setInterval(function () {
            if (type <= 0) {
                clearInterval(timeId);
                s.gameover();
                return;
            }
            type -= 10;
            var miao = Math.floor(type / 1000);
            var haomiao = Math.floor(type % 1000 / 10);
            s.home.time_item1.index = Math.floor(miao / 10);
            s.home.time_item2.index = miao % 10;
            s.home.time_item3.index = Math.floor(haomiao / 10);
            s.home.time_item4.index = haomiao % 10;
        }, 10)
             
    }
    _proto.gameover=function(){
        var s=this;
        Laya.timer.clearAll(s); 
        Laya.Tween.clearAll(s);
        // 移除南瓜层
        s.apeLayer.removeSelf();
        s.GameOver=new gameOver();
            Laya.Tween.to(s.GameOver, {alpha:1}, 5000, null, Laya.Handler.create(s.GameOver, function() {
            s.addChild(s.GameOver);
        }));
        
    }
  
   //头像
   _proto.touXiang=function(){
       // this.tou=new Laya.Image(user_photo);
       // this.tou.width=127;
       // this.tou.height=127;
       // this.tou.pos(15,12);
       // this.addChild(this.tou);
       this.home.wxTx.skin=user_photo;

   }
})()


var isStopTime = false;
(function(){

    var self;
    function data(data){
        data.__super.call(this)
        this.s = data;
        this.init(data);
        this.gold()
    }
    Laya.class(data, "data", laya.display.Sprite)
    data.prototype.init = function(data){
        self = this;
        this.data = data.home;
        this.countDown()
    }
    data.prototype.countDown = function(){
        // 倒计时
        var s = this;
        s.isStop = 0;

        s.mm = parseInt($("#time_config").val()/60);
        s.ms = parseInt($("#time_config").val()%60);
        Laya.timer.frameLoop(60, s, function(){
            if(isStopTime) return;
            if(s.ms==0){
                s.mm--;
                s.ms=60;
            }
            if(s.mm<0){
                s.data.overTime.text = "00:00"
                Laya.timer.clearAll(s);
                    s.isStop++;
                if(s.isStop ==1){
                    s.alertPop()

                }
            }else{
                s.ms -=1;
                if(s.ms<10){
                    s.data.overTime.text = "0"+s.mm+":"+"0"+s.ms;
                }else{
                    s.data.overTime.text = "0"+s.mm+":"+s.ms;
                }
            }
        });
    }
    data.prototype.alertPop = function(){
        this.s.timeOver = true
        this.dlog = new gameOver_DlogUI();
        this.dlog.popup();
        this.dlog.but_overBgColse.on(Laya.Event.MOUSE_DOWN,this.dlog.but_overBgColse,this.onClose);
        this.dlog.but_game.on(Laya.Event.MOUSE_DOWN,this.dlog.but_game,this.onClose);
        this.dlog.but_back.on(Laya.Event.MOUSE_DOWN,this,this.onBackLink);
        operate(this.data.goldMun.text);
    }
    data.prototype.gold = function(jGlod){
         // 金币个数
         jGlod = jGlod || 0;
         this.data.goldMun.text = parseInt(20000)-jGlod
            getcoina(this.data.goldMun);
    }
    data.prototype.onClose = function(){
      self.s.timeOver = false;

        if(this.name=="but_game"){
          ord(self);


        }
        if(this.name=="but_overBgColse"){

            Laya.Tween.to(backLayer,{alpha:0},300,null,Laya.Handler.create(this,function(){
                onLoadUI();
            }));
            backLayer.removeSelf();
            Laya.Tween.to(backLayer,{alpha:1},300,null,null,300)
              self.dlog.close();
        }
    }
    data.prototype.onBackLink = function(){
        alert($("#grzx").val());
        var center_url = $("#grzx").val();
         window.location.href = center_url;
    }
    //获取用户金币数量
    function getcoina(a){
      $.post("Catchfish/getcoin",function (data){
      a.text=data.price;
      },"json");
    }
    //游戏结束提交金币
    function operate(coin){
      $.post("Catchfish/operate",{"coin":coin},function (data){
        if(data.status==1){
          $("#mem_coin").val(data.coin);
          $("game_coin").val(data.game_coin);
        }
      },"json");
    }
    //继续游戏扣钱
    function ord(a){
      $.post("Catchfish/member_cut_coin",function (data){
        if(data.status !=1 ){
          layer.confirm('您的金币不足，是否充值金币？', {
              btn: ['好的', '取消'], //可以无限个按钮

          }, function(index, layero){
              //按钮【按钮一】的回调
              window.location.href=$("#rg").val();
          }, function(index){
              layer.close(index);
          });
        }
        else{

          a.gold(200)
          a.countDown();
            self.dlog.close();
        }
      },"json");
    }
})()

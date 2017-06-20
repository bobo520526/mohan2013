(function(){
    function StratMeun(){
        StratMeun.__super.call(this);
        this.init();
    }
    Laya.class(StratMeun, "StratMeun", laya.display.Sprite)

    StratMeun.prototype.init = function(){
        this.stratMeun = new stratMeunUI();
        this.addChild(this.stratMeun);
        this.stratMeun.but_startGame.on(Laya.Event.MOUSE_DOWN,this,this.onStartGame);
    }
    StratMeun.prototype.onStartGame = function(){
        var self = this;
        if($("#game_status").val() < 1){
            layer.msg("游戏数据解析异常！",{icon:2});
            return ;
        }
        if($("#mem_coin").val()-$("#game_coin").val() <=0){

            layer.confirm('您的金币不足，是否充值金币？', {
                btn: ['好的', '取消'], //可以无限个按钮

            }, function(index, layero){
                //按钮【按钮一】的回调
                window.location.href=$("#rg").val();
            }, function(index){
                layer.close(index);
            });
            return;
        }else{
            $.post("Catchfish/member_cut_coin",function (msg){
                if(msg.status !=1 ){
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
                    self.removeSelf();
                    var homeData = new Bullet()
                    backLayer.addChild(homeData)
                    self.addChild(new data(homeData))
                }
            },"json");
        }

    }

})();
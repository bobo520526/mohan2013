/**
* name
*/
var price;
var kprice;

var GameStart=(function (_super) {
  getcoin();
        function GameStart() {
            GameStart.super(this);
            this.btn_start.on(Laya.Event.MOUSE_DOWN,this,this.startGame);
        };
        Laya.class(GameStart,"GameStart",_super);
        //游戏开始方法
        GameStart.prototype.startGame = function(){

          //游戏状态
          if($("#game_status").val()<=0){

            layer.msg("游戏数据解析异常！",{icon:2});
          return ;
          }
         if(price-kprice<0){//余额不够
           layer.confirm('您的金币不足，是否充值金币？', {
               btn: ['好的', '取消'], //可以无限个按钮

           }, function(index, layero){
               //按钮【按钮一】的回调
               window.location.href="";
           }, function(index){
               layer.close(index);
           });
         }
         else{
           kcoin();
           this.removeSelf();
           LayaSample.game = new Game();
           Laya.stage.addChild(LayaSample.game);
         }

        };
        return GameStart;
})(ui.GameStartUI);
function getcoin(){//获取金币
  $.post("Basketball/getcoin",function (data){

    if(data.status>1){

      price=data.price;
      kprice=data.kprice;
    }
  },"json");
}
function kcoin(){//扣除金币
  $.post("Basketball/member_cut_coin",function (data){
    if(data.status==1){

    }
  },"json");
}

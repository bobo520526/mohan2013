/**
* name 
*/
var Ball=(function () {
        function Ball() {
               return this.addBall();
        };

        var _proto = Ball.prototype;

        //添加篮球方法
        _proto.addBall = function(){
                var ball = new Laya.Sprite();
                ball.loadImage('/Public/game/basketball/bin/image/ball.png');
                var i = Math.floor(Math.random()*10);
                i<3?ball.x = -ball.width:ball.x = 600;
                ball.y = 870;
                return ball;
        };
        return Ball;
})();
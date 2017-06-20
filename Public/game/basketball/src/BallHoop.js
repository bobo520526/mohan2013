/**
* name 
*/
var BallHoop=(function (_super) {
        function BallHoop() {
                BallHoop.super(this);
                this.x = 600;
                this.y = 200;
        }
        Laya.class(BallHoop,"BallHoop",_super);
        var _proto = BallHoop.prototype;

        //添加虚拟篮框
        return BallHoop;
})(ui.Hoop_1UI);
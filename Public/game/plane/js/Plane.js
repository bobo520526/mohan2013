/**
* name 
*/
var Plane= (function () {
        function Plane(pattern) {
            this.reset(pattern);
        }
        var _proto = Plane.prototype;

        _proto.reset = function(pattern){
            this.status = pattern;
        }

        //点击对应的跑道
    _proto.RedLeftDown = function(){
        Gameing.layer.plane_red.status = "left";
        try {
             ws.emit("runplane","red","left");
        } catch (error) {
            
        }
    };

    _proto.RedRightDown =function(){
        Gameing.layer.plane_red.status = "right";
        try {
             ws.emit("runplane","red","right");
        } catch (error) {
            
        }
    }

    _proto.BlueLeftDown = function(){
        Gameing.layer.plane_blue.status = "left";
        try {
             ws.emit("runplane","blue","left");
        } catch (error) {
            
        }
    };

    _proto.BlueRightDown =function(){
        Gameing.layer.plane_blue.status = "right";
        try {
             ws.emit("runplane","blue","right");
        } catch (error) {
            
        }
    }

    //红色飞机的运动
    _proto.PlaneRedRun = function(){
        if(Gameing.layer.plane_red.status == "left" ){
            if(Gameing.layer.plane_red.x <= 72){
                Gameing.layer.plane_red.x = 72;
                Gameing.layer.plane_red.rotation = 0;
                Gameing.layer.plane_red.status = "normal";
                return;
            };
            Gameing.layer.plane_red.rotation = -30;
            Gameing.layer.plane_red.x -=Gameing.step; 
        };
        if(Gameing.layer.plane_red.status == "right" ){
            if(Gameing.layer.plane_red.x >= 227 ){
                Gameing.layer.plane_red.x = 227;
                Gameing.layer.plane_red.rotation = 0;
                Gameing.layer.plane_red.status = "normal";
                return;
            };
            Gameing.layer.plane_red.rotation = 30;
            Gameing.layer.plane_red.x += Gameing.step;
        }
    }

    //蓝色飞机的运动
    _proto.PlaneBlueRun = function(){
        if(Gameing.layer.plane_blue.status == "left" ){
            if(Gameing.layer.plane_blue.x <= 377){
                Gameing.layer.plane_blue.x=377;
                Gameing.layer.plane_blue.rotation = 0;
                Gameing.layer.plane_blue.status = "normal";
                return;
            };
            Gameing.layer.plane_blue.rotation = -30;
            Gameing.layer.plane_blue.x -= Gameing.step; 
        };
        if(Gameing.layer.plane_blue.status == "right" ){
            if(Gameing.layer.plane_blue.x >= 526 ){
                Gameing.layer.plane_blue.x = 526;
                Gameing.layer.plane_blue.rotation = 0;
                Gameing.layer.plane_blue.status = "normal";
                return;
        };
            Gameing.layer.plane_blue.rotation = 30;
            Gameing.layer.plane_blue.x += Gameing.step;
        }
    }

    //移除动画
    _proto.RemoAni =function(){
        Gameing.layer.ani4.stop();
        Gameing.layer.ani5.stop();
        Gameing.layer.ani1.stop();
        Gameing.layer.ani2.stop();
        clearInterval(Gameing.Timer);
        Gameing.gameOver("die")
    }
        return Plane;
})();
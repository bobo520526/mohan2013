/**
* name 
*/
var Barrier = (function () {
        function Barrier(pattern) {
                this.reset(pattern)
        }
        var _proto = Barrier.prototype;

_proto.reset = function(pattern){
        this.status = pattern;
        this.Bb,
        this.Bo,
        this.Rb,
        this.Ro;
        this.BlueBox_Arr = [];
        this.RedBox_Arr = [];
        this.addBarrierNum = 0;
        this.addBarrierNum2 = 90;
}

        //添加红色障碍和宝箱的方法
_proto.addRedBarrierAndBox = function(){
        if(this.status == "onemachine"){
                this.Rb = Math.ceil(Math.random()*10);
                this.Ro = Math.ceil(Math.random()*10);
        }
        var RedBox;
        if(this.Rb < 8){
                //红障碍
                RedBox = new Laya.Image("/Public/game/plane/comp/red_2.png");
                RedBox.y = -RedBox.height;
                RedBox.is = "Barrier";
                if(this.Ro < 5){
                        RedBox.x = 321;
                }
                if(this.Ro >=5){
                        RedBox.x = 465;
                }
                
        };
        if(this.Rb >=8){
                //红宝箱
                RedBox = new Laya.Image("/Public/game/plane/comp/red_1.png");
                RedBox.y = -RedBox.height;
                RedBox.is = "Box";
                if(this.Ro < 5){
                        RedBox.x = 321;
                }
                if(this.Ro >=5){
                        RedBox.x = 465;
                }
        }
        RedBox.zOrder = 20;
        this.RedBox_Arr.push(RedBox);
        Gameing.layer.addChild(RedBox);
}

        //添加蓝色障碍和宝箱的方法
_proto.addBlueBarrierAndBox = function(){
        if(this.status == "onemachine"){
                this.Bb = Math.ceil(Math.random()*10);
                this.Bo = Math.ceil(Math.random()*10);
        }
        var BlueBox;
        if(this.Bb < 8){
                //蓝障碍
                BlueBox = new Laya.Image("/Public/game/plane/comp/blue_2.png");
                BlueBox.y = -BlueBox.height;
                BlueBox.is = "Barrier";
                if(this.Bo < 6){
                        BlueBox.x = 16;
                };
                if(this.Bo >=5){
                        BlueBox.x = 175;
                }
        };
        if(this.Bb >= 8){
                //蓝宝箱
                BlueBox = new Laya.Image("/Public/game/plane/comp/blue_1.png");
                BlueBox.y = -BlueBox.height;
                BlueBox.is = "Box";
                if(this.Bo < 6){
                        BlueBox.x = 16;
                }
                if(this.Bo >=5){
                        BlueBox.x = 175;
                }
        }
        BlueBox.zOrder = 20;
        this.BlueBox_Arr.push(BlueBox);
        Gameing.layer.addChild(BlueBox);
}

//障碍宝箱的移动和超出消失
_proto.BoxRunAndRemove = function(arr1,arr2){
        Gameing.speed+=Gameing.acc/2;
        this.addBarrierNum2-=(Gameing.acc*10);
        if(!Gameing.layer.plane_red.isBang){
                for(var k in arr1){
                arr1[k].y += Gameing.speed;
                if(arr1[k].y >= win_h + arr1[k].height){
                        arr1[k].removeSelf();
                        arr1.splice(k,1);
                }
                }
        }
        if(!Gameing.layer.plane_blue.isBang){
                for(var m in arr2){
                arr2[m].y += Gameing.speed;
                if(arr2[m].y >= win_h + arr2[m].height){
                        arr2[m].removeSelf();
                        arr2.splice(m,1);
                }
                }
        }
}

//障碍物宝箱的碰撞
_proto.BoxCrash = function(Box,Plane){
        for(var p in Box){
                if(util.hitTestRectArc(Box[p],Plane)){
                        if(this.status=="onemachine"){
                                if(Plane.name == "plane_blue" && Box[p].is == "Barrier" && Plane.isCon){
                                        Gameing.layer.ani5.play();
                                        Gameing.layer.ani5.on(Laya.Event.COMPLETE,plane,plane.RemoAni);
                                        Plane.isBang = true;
                                        Gameing.gameStar = false;
                                };
                                if(Plane.name == "plane_red" && Box[p].is == "Barrier" && Plane.isCon){
                                        Gameing.layer.ani4.play();
                                        Gameing.layer.ani4.on(Laya.Event.COMPLETE,plane,plane.RemoAni);
                                        Plane.isBang = true;
                                        Gameing.gameStar = false; 
                                }
                                if(Box[p].is == "Box" && Plane.isCon){
                                        score.score +=  score.BoxScore;
                                }
                                Box[p].removeSelf();
                                Box.splice(p,1)
                                return;
                        }
                        if(Plane.name == "plane_blue" && Plane.isCon){
                                if(Box[p].is == "Barrier"){
                                        Gameing.layer.ani5.play();
                                        Plane.isBang = true;
                                        ws?ws.emit("userdie","roomer",user.score):"";
                                };
                                if(Box[p].is == "Box" && user.Identity == "roomer"){
                                        ws.emit("upscore","roomer")
                                }; 
                        };
                        if(Plane.name == "plane_red" && Plane.isCon){
                                if(Box[p].is == "Barrier"){
                                        Gameing.layer.ani4.play();
                                        Plane.isBang = true;
                                        ws?ws.emit("userdie","roomsOwner",user.score):"";
                                };
                                
                                if(Box[p].is == "Box" && user.Identity == "roomsOwner"){
                                       ws.emit("upscore","roomsOwner")
                                }
                        }
                        Box[p].removeSelf();
                        Box.splice(p,1)
                }
        }
}
        return Barrier;
})();
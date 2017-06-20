    /**
    * name 
    */
    function Game(pattern) {
    this.reset(pattern);
    };
    var _proto = Game.prototype;

    //初始化游戏页面
    _proto.reset = function(pattern){
    ingLayer = new Laya.Sprite();
    backLayer.addChild(ingLayer);
    this.layer = new GameUI();
    ingLayer.addChild(this.layer)
    //障碍
    this.barrier = new Barrier(pattern);
    plane = new Plane();
    this.pattern = pattern;
    //飞机
    this.layer.plane_blue.isBang = false;
    this.layer.plane_red.isBang = false;
    //游戏
    this.gameStar = false;
    this.gameStarNum = 0;
    this.step = 15;//飞机的移动步长
    this.speed = 5;//障碍的移动步长 
    this.acc = 0.01;
    //分数
    score = new Score(pattern);
    Laya.timer.frameLoop(1,this,this.Frame);
    this.layer.timer.zOrder = 50;
    this.layer.start_ani.zOrder = 50;
    this.layer.plane_blue.zOrder = 10;
    this.layer.plane_red.zOrder= 10;
    this.layer.red_score.zOrder = 10;
    this.layer.blue_score.zOrder = 10;

    this.layer.timer._childs[0].index = 2 ;
    this.layer.timer._childs[1].index = 0 ;
    this.layer.ani6.on(Laya.Event.COMPLETE,this,this.chaneStatu)
    this.one = true;
    };

    //根据模式进行事件绑定
    _proto.BinEvent = function(conplane){
    if(conplane == "roomsOwner"){  //房主控制红色飞机
    this.layer.plane_red.isCon = true;
    this.layer.red_left.on(Laya.Event.MOUSE_DOWN,plane,plane.RedLeftDown);
    this.layer.red_right.on(Laya.Event.MOUSE_DOWN,this,plane.RedRightDown);
    return;
    };
    if(conplane == "roomer"){   //房客控制蓝色飞机
    this.layer.plane_blue.isCon = true;
    this.layer.blue_left.on(Laya.Event.MOUSE_DOWN,plane,plane.BlueLeftDown);
    this.layer.blue_right.on(Laya.Event.MOUSE_DOWN,plane,plane.BlueRightDown);
    return;
    };  
    //单机模式
    this.layer.plane_red.isCon = true;
    this.layer.plane_blue.isCon = true;
    this.layer.red_left.on(Laya.Event.MOUSE_DOWN,plane,plane.RedLeftDown);
    this.layer.red_right.on(Laya.Event.MOUSE_DOWN,plane,plane.RedRightDown);
    this.layer.blue_left.on(Laya.Event.MOUSE_DOWN,plane,plane.BlueLeftDown);
    this.layer.blue_right.on(Laya.Event.MOUSE_DOWN,plane,plane.BlueRightDown);
    }

    _proto.chaneStatu = function(){
        this.gameStar = true;
    }

    _proto.upDataScore = function(){
        var RoomerScoreArr = util.ScoreSplie(room.room.users.roomer.score);
        var OwnerScoreArr = util.ScoreSplie(room.room.users.roomsOwner.score);
        this.layer.red_score._childs[0].index = OwnerScoreArr[0];
        this.layer.red_score._childs[1].index = OwnerScoreArr[1];
        this.layer.red_score._childs[2].index = OwnerScoreArr[2];
        this.layer.blue_score._childs[0].index = RoomerScoreArr[0];
        this.layer.blue_score._childs[1].index = RoomerScoreArr[1];
        this.layer.blue_score._childs[2].index = RoomerScoreArr[2];
    }

    //定时器
    _proto.Frame = function(){
    var s = this;
    if(this.gameStar){
    if(this.one){
        this.Timer=setInterval(function(){
            if(s.layer.timer._childs[0].index == 0 && s.layer.timer._childs[1].index == 0){
                clearInterval(s.Timer);
                if(s.pattern =="onemachine"){
                    s.gameOver("win")
                }else{
                    s.gameStar = false;
                    if(user.Identity == "roomsOwner" || (user.Identity == "roomer" && room.room.users.roomsOwner.status == "exit")){
                        ws.emit("gameover",user.Identity,user.score)
                    }
                }
                return;
            }
            if(s.layer.timer._childs[1].index == 0){
                s.layer.timer._childs[1].index = 9;
                s.layer.timer._childs[0].index = --(s.layer.timer._childs[0].index);
            }else{
                s.layer.timer._childs[1].index = --(s.layer.timer._childs[1].index);
            };
        },1000);
        //控制判断
        this.BinEvent(this.pattern);
        s.layer.ani1.play();
        s.layer.ani2.play();
        this.gameStar = true;
        if(this.barrier.status != "onemachine" && s.layer.plane_red.isCon == true){
            ws.emit("gamestart");
        };
        this.one = false;
    }

    if(this.layer.plane_red.x >= 71-this.step && this.layer.plane_red.x <= 230+this.step ){
        plane.PlaneRedRun();
    }
    if(this.layer.plane_blue.x >= 376-this.step && this.layer.plane_blue.x <= 526+this.step){
        plane.PlaneBlueRun();
    }
    //障碍
    if(this.barrier.status == "onemachine"){
        this.barrier.addBarrierNum++;
        if(this.barrier.addBarrierNum >= this.barrier.addBarrierNum2){
            if(!this.layer.plane_red.isBang && !this.layer.plane_blue.isBang){
                this.barrier.addBlueBarrierAndBox();
                this.barrier.addRedBarrierAndBox();
            }
            this.barrier.addBarrierNum = 0;
        };
    };
    this.barrier.BoxRunAndRemove(this.barrier.BlueBox_Arr,this.barrier.RedBox_Arr);
    // 飞机碰撞
    if(!this.layer.plane_red.isBang){
        this.barrier.BoxCrash(this.barrier.BlueBox_Arr,this.layer.plane_red);
    };
    if(!this.layer.plane_blue.isBang){
        this.barrier.BoxCrash(this.barrier.RedBox_Arr,this.layer.plane_blue);
    }
    return;
    };
    }

    _proto.gameOver = function(type){
        ingLayer.removeSelf();
        Gameing.gameStar = false;
        Game_over_one = new GameOverOne(type);
    }
(function(){
    function Fish(){
        Fish.__super.call(this)
        this.init()
    }
    Laya.class(Fish, "Fish", laya.display.Sprite)


    Fish.prototype.init = function(){
        this.addTime = 0;
        this.FishArr = [
            "/Public/game/catchfish/res/atlas/anim/fish1.json",
            "/Public/game/catchfish/res/atlas/anim/fish2.json",
            "/Public/game/catchfish/res/atlas/anim/fish3.json",
            "/Public/game/catchfish/res/atlas/anim/fish4.json",
            "/Public/game/catchfish/res/atlas/anim/fish5.json",
            "/Public/game/catchfish/res/atlas/anim/fish6.json",
            "/Public/game/catchfish/res/atlas/anim/fish7.json",
            "/Public/game/catchfish/res/atlas/anim/fish8.json",
            "/Public/game/catchfish/res/atlas/anim/fish9.json",
            "/Public/game/catchfish/res/atlas/anim/fish10.json",
        ]
        this.fishMoveSeep = [0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9]
        this.fishMoveY    = [100,120,150,160,170,180,190,200,210,220,230,240,250,260,270,280,290,300,350,450,500,550,600,650,700,750,800]
        // 实例化鱼层
        this.fishsLayer = new Laya.Sprite()
        this.addChild(this.fishsLayer)
        this.animLayer = new Laya.Sprite();
        this.addChild(this.animLayer);
        //增加鱼方法
        this.addFish(5);

        //循环计时器
        Laya.timer.frameLoop(1,this,this.onRun);
    }
    Fish.prototype.addFish = function(index){
        var self = this;;
        var s = this;
        //实例动画模板

        this.Fishs = new Laya.Animation();
        // 根据index来判断要放入那一条鱼

        this.Fishs.loadAtlas(s.FishArr[parseInt(index)]);
        this.Fishs.autoSize = true;
        this.Fishs.play();//鱼动画播放
        this.Fishs.autoSize = true;//自动获取鱼的大小
        this.Fishs.interval =250;//鱼的动画播放间隔
        this.Fishs.moveX = this.fishMoveSeep[parseInt(Math.round(Math.random()*10))]
        this.Fishs.pos(-60,this.fishMoveY[parseInt(Math.round(Math.random()*this.fishMoveY.length))]) //鱼的初始位置
        this.Fishs.name = "fish";
        this.fishsLayer.addChild(this.Fishs); //把鱼添加到舞台
        this.Fishs.getIndex = parseInt(index);//存储当前鱼的大小
        this.Fishs.HP = parseInt(index);
        this.Fishs.stop = false
    }
    Fish.prototype.onRun = function(){
        var s = this;
        //  间隔100时执行this.addFish(name) 增加一条鱼
        this.addTime++;
        if(this.addTime>=40){
            this.addTime=0;
            this.ra =  Math.floor(Math.random()*100)
            if(this.ra >=80 && this.ra <100){
                name = 0
            }
            if(this.ra >=60 && this.ra <80){
                name= 1
            } if(this.ra >=40 && this.ra <60){
                name= 2
            } if(this.ra >=20 && this.ra <40){
                name = 3
            } if(this.ra >=10 && this.ra <20){
                name = 4
            } if(this.ra >=5 && this.ra <8){
                name = 5
            }if(this.ra >=3 && this.ra <5){
                name = 7
            }if(this.ra >=1 && this.ra <3){
                name = 8
            }if(this.ra ==0){
                name = 9
            }
            this.addFish(name)
        }
        for(key in s.fishsLayer._childs){ //找到所有的鱼
            if(s.fishsLayer._childs[key].index>=3){
                s.fishsLayer._childs[key].index=0;
            }
            //鱼的移动轨迹
            if(!s.fishsLayer._childs[key].stop){
                s.fishsLayer._childs[key].x+= s.fishsLayer._childs[key].moveX*2;
                s.fishsLayer._childs[key].y+= s.fishsLayer._childs[key].moveX-0.5;
            }
            //判断鱼是否超出屏幕，超出则移除
            if(s.fishsLayer._childs[key].y<=-s.fishsLayer._childs[key].height || s.fishsLayer._childs[key].x<=-200 || s.fishsLayer._childs[key].x>WIN_W){
               s.fishsLayer._childs[key].removeSelf()
            //    console.log("移除自己aaa")
            }
        }
    }


})()

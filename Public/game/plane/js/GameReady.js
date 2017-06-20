/**
* name 
*/
    function GameReady() {
        this.reset();
    }
    var _proto = GameReady.prototype;

    _proto.reset = function(){
        readyLayer = new Laya.Sprite();
        startLayer.addChild(readyLayer);
        this.layer = new GameReadyUI();
        readyLayer.addChild(this.layer);
        this.BinEvent();
        this.layer.addFriend.visible = true;
        this.layer.invite.visible = true;
    };
    
    _proto.BinEvent = function(){
        this.layer.invite.on(Laya.Event.MOUSE_DOWN,this,this.GameFight);
        this.layer.addFriend.on(Laya.Event.MOUSE_DOWN,this,this.GameFight);
        this.layer.roomerReady.on(Laya.Event.MOUSE_DOWN,this,this.gameReady);
        this.layer.game_start.on(Laya.Event.MOUSE_DOWN,this,this.gameStart);
        this.layer.tcyx.on(Laya.Event.MOUSE_DOWN,this,this.gameTcyx);
    }

    _proto.gameTcyx = function(){
        ws.disconnect();
        history.replaceState(null,'',location.pathname+location.search);
        socket=null;
        ws=null;
        room= new Room();
        user.Identity =null;
        readyLayer.removeSelf();
    }

    _proto.GameFight = function(){
        //弹出分享邀请框
    }
    
    _proto.gameReady = function(){
        ws.emit("ready",user.Identity);
        // this.layer.roomerReady.visible = false;
        // this.layer.qxzb.visible = true ;
        util.showBtn(this.layer.qxzb)
    }

    _proto.gameStart = function(){
        ws.emit("ready",user.Identity);
    }
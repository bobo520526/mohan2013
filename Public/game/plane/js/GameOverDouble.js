/*
* name;
*/
    function GameOverDouble() {
        this.reset();
    }
    var _proto = GameOverDouble.prototype;
    _proto.reset = function(type){
        overDoubleLayer = new Laya.Sprite();
        backLayer.addChild(overDoubleLayer);
        this.layer = new GameOverDoubleUI();
        overDoubleLayer.addChild(this.layer);
        this.layer.goBack.on(Laya.Event.MOUSE_DOWN,this,this.GoBack);
        this.showMyself();
        this.showOther();
        this.showScore();
    }

    _proto.showMyself= function(){
        this.self = room.room.users[user.Identity];
        var Avatar_view = new Laya.Image(this.self.Avatar);
        Avatar_view.width = 100;
        Avatar_view.height = 100;
        Avatar_view.x = 18 ; 
        Avatar_view.y = 18;
        this.layer.Self_avatar.addChild(Avatar_view);
    };
    _proto.showOther = function(){
        this.other = user.Identity == "roomer"?room.room.users.roomsOwner:room.room.users.roomer;
        var Avatar_view = new Laya.Image(this.other.Avatar);
        Avatar_view.width = 100;
        Avatar_view.height = 100;
        Avatar_view.x = 18 ; 
        Avatar_view.y = 18;
        this.layer.Other_avatar.addChild(Avatar_view);
    };
    _proto.showScore = function(){
         //房主的分数
        var OwnerScoreArr = util.ScoreSplie(room.room.users.roomsOwner.score);
        var RoomerScoreArr = util.ScoreSplie(room.room.users.roomer.score);
        // this.RoomsOwnerHundredsNum = Math.floor(room.roomsOwner.score / 100);
        // this.RoomsOwnerDecadeNum = Math.floor((room.roomsOwner.score - (this.RoomsOwnerHundredsNum*100)) / 10);
        // this.RoomsOwnerUnitNum = room.roomsOwner.score - (this.RoomsOwnerHundredsNum*100+this.RoomsOwnerDecadeNum *10);
        //房客的分数
        // this.RoomerHundredsNum = Math.floor(room.roomer.score / 100);
        // this.RoomerDecadeNum = Math.floor((room.roomer.score - (this.RoomerHundredsNum*100)) / 10);
        // this.RoomerUnitNum = room.roomer.score - (this.RoomerHundredsNum*100+this.RoomerDecadeNum *10);
        this.layer.roomsOwner_score._childs[0].index = OwnerScoreArr[0];
        this.layer.roomsOwner_score._childs[1].index = OwnerScoreArr[1];
        this.layer.roomsOwner_score._childs[2].index = OwnerScoreArr[2];
        this.layer.roomer_score._childs[0].index = RoomerScoreArr[0];
        this.layer.roomer_score._childs[1].index = RoomerScoreArr[1];
        this.layer.roomer_score._childs[2].index = RoomerScoreArr[2];
    }
    //返回主页
    _proto.GoBack=function(){
        ws.emit("goback");
    }

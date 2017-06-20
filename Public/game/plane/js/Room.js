/**
* name 
*/
        function Room() {
            this.reset();
        }
        var _proto = Room.prototype;

        _proto.reset = function(){
            this.room= {};
            this.status = "normal";
        };

         _proto.showOwner = function(){
            if(!this.room.users.roomsOwner && this.roomsOwnerView){
                this.roomsOwnerView.removeSelf();
                return;
            };
            this.roomsOwnerView = new Laya.Image(this.room.users.roomsOwner.Avatar);
            var ImageID = new Laya.Image("gameReady/userID_font.png");
            ImageID.x= 100;
            ImageID.y = 40;
            var NickName = new Laya.Text();
            NickName.text = this.room.users.roomsOwner.nickName;
            NickName.x = 90;
            NickName.y = 35;
            NickName.width = 300;
            NickName.height = 50;
            NickName.color = "#12E2F0"
            NickName.fontSize = 30;
            NickName.align = "center";
            this.roomsOwnerView.addChild(NickName)
            this.roomsOwnerView.addChild(ImageID);
            this.roomsOwnerView.x = 15;
            this.roomsOwnerView.y = 15;
            Game_ready.layer.houseOwner.addChild(this.roomsOwnerView);
            if(user.Identity == "roomsOwner"){
                Game_ready.layer.ani1.play();
                util.showBtn(Game_ready.layer.invite);
            };
            if(user.Identity == "roomsOwner"&& this.room.users.roomer && this.room.users.roomer.status == "readying"){
                util.showBtn(Game_ready.layer.game_start);
            }
        }

        _proto.showRoomer = function(){
            if(!this.room.users.roomer && this.RoomerView){
                this.RoomerView.removeSelf();
                return;
            };
            this.RoomerView = new Laya.Image(this.room.users.roomer.Avatar);
            var ImageID = new Laya.Image("gameReady/userID_font.png");
            var NickName = new Laya.Text();
            NickName.text = this.room.users.roomer.nickName;
            NickName.x = 90;
            NickName.y = 35;
            NickName.width = 300;
            NickName.height = 50;
            NickName.color = "#12E2F0"
            NickName.fontSize = 30;
            NickName.align = "center";
            this.RoomerView.addChild(NickName)
            ImageID.x= 100;
            ImageID.y = 40;
            this.RoomerView.addChild(ImageID);
            this.RoomerView.x = 15;
            this.RoomerView.y = 15; 
            Game_ready.layer.roomer.addChild(this.RoomerView);
            Game_ready.layer.invite.visible = false ;
            Game_ready.layer.addFriend.visible =false;
            if(user.Identity == "roomsOwner" && this.room.users.roomer &&this.room.users.roomer.status != "readying"){
                util.showBtn(Game_ready.layer.waitReady);
            }
            if(user.Identity == "roomer"){
                util.showBtn(Game_ready.layer.roomerReady);
            }
        };
        _proto.updataRoomOwner = function(){
            console.log(this.room.users)
            this.showOwner();
            this.showRoomer();
        }

        _proto.RoomerDie = function(){
            Gameing.layer.ani5.stop();
            Gameing.layer.plane_blue.visible = false;
        }

        _proto.RoomsOwnerDie = function(){
            Gameing.layer.ani4.stop();
            Gameing.layer.plane_red.visible = false;
        }
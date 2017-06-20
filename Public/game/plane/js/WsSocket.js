/**
* name 
*/
var WsSocket = (function () {
        function WsSocket() {
            this.connection();
        }
        var _proto = WsSocket.prototype;

        _proto.connection = function(){
            if(ws){
                //房间创建
                ws.on("creatroom",(result)=>{
                    room.room = result;
                    user = result.users.roomsOwner;
                    Game_ready = new GameReady();
                    if(Game_start.roomID != result.roomID){
                        window.location.href = window.location.origin + window.location.pathname + ("#roomid="+result.roomID );
                        Game_start.roomID=result.roomID;
                    };
                    room.showOwner();
                });
                //用户加入房间监听
                ws.on("addroomer",(result)=>{
                    room.room = result;
                    room.status = "readying"
                    if(!user.Identity){
                        user = result.users.roomer;
                    }
                    if(!Game_ready){
                        Game_ready = new GameReady();
                    }
                    room.updataRoomOwner();
                });
                //监听房间状态
                ws.on("noroom",(result)=>{
                    Game_start.roomID = null;
                    window.location.href = window.location.origin+window.location.pathname+"#";
                    Game_start.layer.ani1.play();
                    ws.disconnect();
                    Game_start.layer.ani1.on(Laya.Event.COMPLETE,Game_start,Game_start.remoAni)
                });

                //监听用户退出房间
                ws.on("exit",(users)=>{//只有房间正在游戏中
                    if(room.room.status != "playing"){
                        room.room.users = users;
                        user.Identity = "roomsOwner"
                        room.updataRoomOwner();
                        return;
                    };
                    // console.log(Room)
                    room.room.users = users;
                    // if(room.room.status=="playing" && User == "roomsOwner" && user.Identity == "roomsOwner"){
                    //     room.room.exitOwner = room.room.roomsOwner;//逃跑的用户
                    //     room.room.roomsOwner = null;
                    //     return;
                    // };
                    // if(room.room.status=="playing" && User == "roomer" && user.Identity == "roomer"){
                    //     room.room.exitRoomer = room.room.roomer;//逃跑的用户
                    //     room.room.roomer = null;
                    //     return;
                    // };
                    // if(room.room.status != "playing" ){
                    //     room.room.status = "normal"
                    // }
                    // if(User === user.Identity){
                    //     room = null;
                    //     user.Identity =null;
                    // }else if(User == "roomsOwner" && user.Identity == "roomer"){//退出的是房主
                    //     room.roomsOwnerView.removeSelf();
                    //     room.room.users.roomsOwner = room.room.roomer;
                    //     user.Identity = "roomsOwner";
                    //     room.room.users.roomer= null;
                    //     room.updataRoomOwner();
                    // }else if(User  == "roomer" && user.Identity == "roomsOwner"){//退出的是房客
                    //     room.room.users.roomer = null;
                    //     room.updataRoomOwner();
                    // }
                })

                //准备监听
                ws.on("ready",(msg)=>{
                    if(msg == "roomsuser noready" && user.Identity == "roomsOwner"){
                        Game_ready.layer.waitReady.visible = true ;
                        Game_ready.layer.ani1.play();
                        Game_ready.layer.game_start.visible =false ;
                        return;
                    }
                    if(msg == "roomsuser ready" && user.Identity == "roomsOwner"){
                        Game_ready.layer.waitReady.visible = false ;
                        Game_ready.layer.ani1.stop();
                        Game_ready.layer.game_start.visible =true ;
                    }
                });

                //游戏开始监听
                ws.on("gamestart",()=>{
                    startLayer.removeSelf();
                    readyLayer.removeSelf();
                    Gameing = new Game(user.Identity);
                    room.room.status ="playing";
                });

                //飞机运动
                ws.on("runplane",(plane,ori)=>{
                    if(plane == "red"){
                        if(ori == "left"){
                            Gameing.layer.plane_red.status = "left"
                        };
                        if(ori == "right"){
                            Gameing.layer.plane_red.status = "right"
                        }
                    };
                    if(plane == "blue"){
                        if(ori == "left"){
                            Gameing.layer.plane_blue.status = "left"
                        };
                        if(ori == "right"){
                            Gameing.layer.plane_blue.status = "right"
                        }
                    }
                });

                //障碍宝箱添加监听
                ws.on("addbox",(box,b,o)=>{
                    if(Gameing){
                        if(box == "redBox"){
                            Gameing.barrier.Rb = b;
                            Gameing.barrier.Ro = o;
                            Gameing.barrier.addRedBarrierAndBox();
                        };
                        if(box == "blueBox"){
                            Gameing.barrier.Bb = b;
                            Gameing.barrier.Bo = o;
                            Gameing.barrier.addBlueBarrierAndBox();
                        };
                    }

                });

                //分数监听
                ws.on("upscore",(User,score)=>{
                    // var other;
                    // console.log(user.Identity,User);
                    // if(user.Identity == User){
                    //     room.room.users[User].score = score;
                    // }else if(user.Identity != User){
                    //     other= User=="roomer"?"roomsOwner":"roomer";
                    //     console.log(other)
                    //     console.log(room.room.users)
                    //     room.room.users[other].score = score;
                    // };
                    room.room.users[User].score = score;
                    Gameing.upDataScore();
                })
                //死亡监听
                ws.on("userdie",(plane)=>{
                    if(plane == "roomer"){
                        Gameing.layer.ani5.play();
                        Gameing.layer.plane_blue.isBang = true;
                        Gameing.layer.ani5.on(Laya.Event.COMPLETE,room,room.RoomerDie);
                        Gameing.layer.shade_right.visible = true;
                        Gameing.layer.shade_right.zOrder = 30;
                        //蓝色飞机死亡之后的操作
                    };
                    if(plane == "roomsOwner"){
                        Gameing.layer.ani4.play();
                        Gameing.layer.ani4.on(Laya.Event.COMPLETE,room,room.RoomsOwnerDie);
                        Gameing.layer.plane_red.isBang = true;
                        Gameing.layer.shade_left.visible = true;
                        Gameing.layer.shade_left.zOrder = 30;
                        //红色飞机死亡之后的操作
                    }
                });

                //游戏结束监听
                ws.on("gameover",(result)=>{
                    room.room.users.roomer = result.roomer;
                    room.room.users.roomsOwner = result.roomsOwner;
                    room.room = result;
                    ingLayer.removeSelf();
                    Game_over_double = new GameOverDouble();
                    room.room.status = "gameover";
                });


                ws.on("goback",(result)=>{
                    if(!result.users.roomer){
                        if(user.Identity == "roomer"){
                            user.Identity = "roomsOwner"
                        }
                    }
                    room.room = result;
                    overDoubleLayer.removeSelf();
                    Game_start.reset();
                    Game_ready.reset();
                    room.updataRoomOwner();
                })

                ws.on("outcome",(outcome)=>{
                    console.log(outcome)
                    if(outcome == "peace"){
                        Game_over_double.layer.peace.visible = true;
                        return;
                    };
                    if(outcome == user.Identity){
                        Game_over_double.layer.self_win.visible = true;
                    };
                    if(outcome != user.Identity){
                        Game_over_double.layer.other_win.visible = true;
                    }
                })
            }
        }
        return WsSocket;
})();
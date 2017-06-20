/**
* name 
*/
        function GameOverOne(type) {
            this.reset(type);
        }
        var _proto = GameOverOne.prototype;
        _proto.reset = function(type){
            overOneLayer = new Laya.Sprite();
            backLayer.addChild(overOneLayer);
            this.layer = new GameOverOneUI();
            overOneLayer.addChild(this.layer);
            this.hundredsNum = Math.floor(score.score / 100);
            this.decadeNum = Math.floor((score.score - (this.hundredsNum*100)) / 10);
            this.unitNum = score.score - (this.hundredsNum*100+this.decadeNum *10);
            this.layer.Score_oo._childs[0].index = this.hundredsNum;
            this.layer.Score_oo._childs[1].index = this.decadeNum;
            this.layer.Score_oo._childs[2].index = this.unitNum;
            this.layer.golds._childs[0].index = this.hundredsNum;
            this.layer.golds._childs[1].index = this.decadeNum;
            this.layer.golds._childs[2].index = this.unitNum;
            if(type == "die"){
                this.layer.los.visible = true;
            }
            if(type == "win"){
                this.layer.win_.visible = true;
            };
            if(score.score >= 10){
                this.layer.star_1.visible = true;
            };
            if(score.score >= 20){
                this.layer.star_2.visible = true;
            };
            if(score.score >= 30){
                this.layer.star_3.visible = true;
            }
            this.layer.again.on(Laya.Event.MOUSE_DOWN,this,this.Again);
            this.layer.goBack.on(Laya.Event.MOUSE_DOWN,this,this.GoBack)
        }

        _proto.Again = function(){
            overOneLayer.removeSelf();
            Gameing = new Game("onemachine");
        }

        _proto.GoBack = function(){
            overOneLayer.removeSelf();
            Game_start = new GameStart();
        }
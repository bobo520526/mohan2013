
var Rechange = (function (_super) {
    function Rechange() {
        Rechange.super(this);
        this.init()
    };
    Laya.class(Rechange, "Rechange", _super);
    var _proto = Rechange.prototype;
    _proto.init = function () {
        this.money = new rechangesUI();
        this.money.popup(); //show()//
        this.money.closeDialog.on(Laya.Event.MOUSE_DOWN, this, this.close);
        this.money.btn_5.on(Laya.Event.MOUSE_DOWN, this, this.AddMoney1);
        this.money.btn_10.on(Laya.Event.MOUSE_DOWN, this, this.AddMoney2);
        this.money.btn_20.on(Laya.Event.MOUSE_DOWN, this, this.AddMoney3);
        this.money.btn_100.on(Laya.Event.MOUSE_DOWN, this, this.AddMoney4);
        this.money.btn_1000.on(Laya.Event.MOUSE_DOWN, this, this.AddMoney5);

    }

    _proto.close = function () {
        this.money.close(); //show()//
    }

    //充值金额

    _proto.AddMoney1 = function () {
        console.log(5)
    }
    _proto.AddMoney2 = function () {

    }
    _proto.AddMoney3 = function () {

    }
    _proto.AddMoney4 = function () {

    }
    _proto.AddMoney5 = function () {

    }

    return Rechange;
})(ui.rechangesUI);
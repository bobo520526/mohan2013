<?php
class Config{
    private $cfg = array(
        'url'=>'https://pay.swiftpass.cn/pay/gateway',
        'mchId'=>'101510051915',
        'key'=>'18d299b337a1d717cb9327a8a2e364bc',
        'version'=>'2.0'
       );
    
    public function C($cfgName){
        return $this->cfg[$cfgName];
    }
}
?>
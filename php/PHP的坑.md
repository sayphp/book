> PHP是一个写起来很方便、快捷的语言，但也正因为如此，存在一些奇奇怪怪的坑，这里开一篇文章收集自己再开发过程中遇到的坑

### 1.==

```php
//业务代码更繁杂，以下是简化后代码
$batchId = md5($id);

//*构造通知下发信息
//batchId为md5后生成的32为字符串
function getSendInfo($uid, $tradeId, $subTradeId, $prodcutId, $batchId=1){
    //*参数验证
    //*构造信息
    $addressInfo = getAddressInfo($uid, $tradeId, $subTradeId);/
    if($batchId==1) $batchId = md5($batchId);
    $data = [
        'uid' => $uid,
        'productId' => $prodcutId,
        'addressInfo' => $addressInfo,
        'batchId' => $batchId
    ];
    if(checkBatchId($batchId)） erro('批次号错误'); 
    return $data;
}
```

批次号（batchId），用于告知物流系统按照什么规则去打包寄送实物。

由于历史原因，原本想要对没有传入批次号的订单生成默认批次号，保证物流顺利下发；然而，后期为了保证履约正确性，追加了批次号验证。

而根据历史数据观察后，发现有部分批次号被两次md5。奇怪了半天，研究了下，后来发现踩到了PHP的坑。

```php
//*判断下面结果是否为true
$batchId = md5(112014);//1c5d78fc34c33df8f7c4af48ddb98618
var_dump($batchId==1);//true
var_dump($batchId=='1');//false
var_dump(1=='1');//true
var_dump($batchId===1);//false
var_dump($batchId==='1');//false
var_dump('1'===1);//false
```

这是一个字符串（string）和数字（int）比较的坑，使用`==`的时候，字符串会从起始位置截取连续数字，与后面的数字进行比较，而我生成的批次号，恰好踩到了这个点上，大意了-。-
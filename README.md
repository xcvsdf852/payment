#LOCK IN SHARE MODE SLEEP 位置 測試心得

![Alt text](https://github.com/xcvsdf852/payment/blob/master/doc/Sleep_test.PNG "測試")


+ Sleep設置5秒

+ 當Sleep位置在SELECT之前，一起動作時A與B一起寫入，等待一秒時A與B相差兩秒。


+ 當Sleep位置在UPDATE之前，不管是一起或是相差一秒鐘，B的測試結果都失敗。推測兩種可能
 1. 可能當第一個LOCK IN SHARE MODE SLEEP後，第二個無法讀取進行，向FOR UPDATE功能一樣鎖住UPDATE
 2. 可能兩個都能讀取，但當要UPDATE時第一個還未結束並解鎖，第二個也要UPDATE所以失敗

+ 當Sleep位置在INSERT之前，一起動作時與等待一秒時，都是相差五秒，表示A與B都已完成存款，
 等待新增紀錄。

###當Sleep位置在UPDATE之前，一起動作時，SELECT與UPDATE的狀態
![Alt text](https://github.com/xcvsdf852/payment/blob/master/doc/update.PNG "一起動作時")

###當Sleep位置在UPDATE之前，相差一秒鐘時，SELECT與UPDATE的狀態
![Alt text](https://github.com/xcvsdf852/payment/blob/master/doc/update-diff1s.PNG "相差一秒鐘時")

+ 根據兩個結果，LOCK IN SHARE MODE後是可以被查詢的，但當要一起UPDATE時第一個未解鎖，
 第二個要執行就會失敗。

###增加IE測試
![Alt text](https://github.com/xcvsdf852/payment/blob/master/doc/IE_test.PNG "相差一秒鐘時")
+ 結果相同

#移除 LOCK IN SHARE MODE SLEEP 位置 測試心得

![Alt text](https://github.com/xcvsdf852/payment/blob/master/doc/delete_LOCK_IN_SHARE_MODE.PNG "移除 LOCK IN SHARE MODE SLEEP")

+ 當Sleep位置在UPDATE與INSERT之前，都可成功執行，但明細與實際金額不相符!
 1. 沒有鎖SELECT導致，A與B都讀出金額0，然後新增明細時，就都只會加100塊
 2. 在實際金額方面都會讀取目前欄位實際金額然後相加，因此實際金額相符200塊


#使用flag 測試心得
![Alt text](https://github.com/xcvsdf852/payment/blob/master/doc/flag_test_sleep.PNG "sleep測試結果")
+ 執行都可成功執行，且明細與實際金額相符!

###嘗試[網址範例](http://sls.weco.net/node/21326)問題測試，餘額1000，同時取500，存800，金額與明細相符
![Alt text](https://github.com/xcvsdf852/payment/blob/master/doc/flag_deposit_withdrawals.PNG "sleep測試結果")

###同時扣款，超出金額，也成功阻擋
![Alt text](https://github.com/xcvsdf852/payment/blob/master/doc/flag.PNG "超出金額測試")

+ 發現，用這個方式防錯方面可以達到LOCK IN SHARE MODE SLEEP相同效果。
 1. 在sleep在UPDATE之前的失敗也不會發生
 2. 成功的同時，明細與實際金額也能對的上

###壓力測試
![Alt text](https://github.com/xcvsdf852/payment/blob/master/doc/apache-benchmark.jpg "同時測試存取款")

+ 同時對同一個帳戶執行存款100元與取款5元
 1. 存款成功執行 所以應該成功存款10000元
 2. 取款失敗88筆 所以應該少取440元

![Alt text](https://github.com/xcvsdf852/payment/blob/master/doc/result.png "明細與實際金額相符")

+ 同時核對明細與資料庫實際金額，與預測金額相符
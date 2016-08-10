#LOCK IN SHARE MODE SLEEP 位置 測試心得

![Alt text](https://github.com/xcvsdf852/payment/blob/master/doc/Sleep_test.PNG "測試")


+Sleep設置5秒

+當Sleep位置在SELECT之前，一起動作時A與B一起寫入，等待一秒時A與B相差兩秒。


+當Sleep位置在UPDATE之前，不管是一起或是相差一秒鐘，B的測試結果都失敗。推測兩種可能
1. 可能當第一個LOCK IN SHARE MODE SLEEP後，第二個無法讀取進行，向FOR UPDATE功能一樣鎖住UPDATE
2. 可能兩個都能讀取，但當要UPDATE時第一個還未結束並解鎖，第二個也要UPDATE所以失敗

+當Sleep位置在INSERT之前，一起動作時與等待一秒時，都是相差五秒，表示A與B都已完成存款，
 等待新增紀錄。

###當Sleep位置在UPDATE之前，一起動作時，SELECT與UPDATE的狀態
![Alt text](https://github.com/xcvsdf852/payment/blob/master/doc/update.PNG "一起動作時")

###當Sleep位置在UPDATE之前，相差一秒鐘時，SELECT與UPDATE的狀態
![Alt text](https://github.com/xcvsdf852/payment/blob/master/doc/update-diff1s.PNG "相差一秒鐘時")

+根據兩個結果，LOCK IN SHARE MODE後是可以被查詢的，但當要一起UPDATE時第一個未解鎖，
 第二個要執行就會失敗。

###增加IE測試
![Alt text](https://github.com/xcvsdf852/payment/blob/master/doc/IE_test.PNG "相差一秒鐘時")
+ 結果相同
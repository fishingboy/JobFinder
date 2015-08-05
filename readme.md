## 找工作小工具

	因為覺得 104 的求職條件只能設 5 組不太夠用
	而且無法根據公司來做收合，無法快速瀏灠符合條件的公司有哪些？
	也無法依照公司人數或資本額或其他欄位進行排序
	也無法記錄職缺的已讀狀態，所以經常都要重頭再看一次所有的職缺
	以及儲存的工作或公司無法快速的排序排名
	或是對該條件下的工作做二次搜尋

	另外還想做其他資料來源，如 PTT 的爬蟲
	指定特定的討論版之後，設定結束日期，然後自動去爬資料
	如搜尋標題為 [徵才] 文章，再進去爬文章的內容
	一樣方便做搜尋

	後來和前公司的朋友分享這個工具
	他們覺得很有趣，所以也加入了
	目前是四個人的專案在進行

註：
* 使用 Laravel PHP Framework
* 使用 104 API : http://www.104.com.tw/i/api_doc/jobsearch/

安裝步驟：請參考 install.txt


### 開始在專案中使用gulp

1. 安裝 [node.js](https://nodejs.org/)

2. 在command line 切到專案目錄 (ex: /JobFinder) 輸入:
> npm install

3. 執行gulp的工作
> gulp {task name}

4. 讓gulp開始監看檔案，執行後請勿關閉command line
> gulp watch

5. gulp watch 執行後 只要有監看到的檔案，儲存後都會自動佈署

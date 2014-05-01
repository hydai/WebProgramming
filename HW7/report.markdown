#Web - HW7 - Report
##程式碼架構
####架構示意圖：
	.
	├── Database
	│  	└── dbInc.php
	├── User
	│  	├── index.php
	│  	├── sentPost.php
	│  	├── sendReply.php
	│  	├── replyPost.php
	│  	└── deletePost.php
	├── Account
	│  	├── login.php
	│  	├── logout.php
	│  	├── update.php
	│  	├── updateF.php
	│  	├── reg.php
	│  	└── register.php
	└── Friend
	   	├── addFriend.php
	   	├── removeFriend.php
	   	├── home.php
	   	└── findFriend.php
####說明
* dbInc.php 放置與資料庫連接的部分，如相關的設定與帳號密碼等訊息，若有需要進行連線，再引入他來使用。
* index.php 為用戶登入後首頁，主要顯示用戶資訊與他自己的訊息。
* findFriend.php 是 index 的尋找使用者的搜尋，主要以 ACCOUNT 為鍵值。
* sentPost.php, sendReply.php, replyPost.php deletePost.php 為操縱使用者發文與回覆，會根據該篇文章的所有者與回覆對象等狀態放入或移除在資料庫中。
* login.php 主要讓使用者進行登入，對密碼部分到後端使用 md5 加密。
* logout.php 摧毀 SESSION 讓使用者登出。
* update.php updateF.php 讓使用者能更新自己帳號相關的功能，若要改密碼會先要求他輸入原本密碼。對密碼也有長度上的要求。
* reg.php, register.php 讓使用者能註冊，在後端會驗證帳號名稱、密碼等是否符合規定，若非，則警告使用者並要求更正。
* addFriend.php, removeFriend.php 用來新增與刪除朋友，主要會在 fakebook.FRIEND 裡面加入 MASTER, SLAVE pair 來驗證朋友關係。
* home.php 每個用戶的塗鴉牆顯示個人的訊息，若為好友關係則會出現好友限定的訊息。

##資料庫架構
	fakebook
	├── USER
	│  	├── ID
	│  	├── ACCOUNT
	│  	├── PWD
	│  	├── NAME
	│  	├── NICKNAME
	│  	├── SEX <- it is not using.
	│  	└── EMAIL
	├── MESSAGE
	│  	├── OWNERID
	│  	├── TYPE
	│  	├── MESSAGE
	│  	├── POSTID
	│  	└── MASTERID
	└── FRIEND
	   	├── MASTER
	   	└── SLAVE
####說明
* 資料庫名稱為 fakebook ，裡頭有三個 table 分別儲存使用者資訊、所有的訊息、朋友關係。
* USER 儲存使用者關係
	* ID: 是 AUTO_INCREMENT ，每個新的使用者都會被賦予一個獨一無二的 ID。
	* ACCOUNT: 使用者自定義的賬戶名稱，為獨一無二的鍵值。
	* PWD: 使用者的密碼，以 md5 加密後儲存。
	* NAME: 使用者真實姓名。
	* NICKNAME: 使用者暱稱。
	* SEX: 目前尚未使用，預設為 0 。
	* EMAIL: 使用者的電郵。
* MESSAGE: 用來儲存所有訊息
	* OWNERID: 該訊息的擁有者 ID 以便於呈現使用者的動態。
	* TYPE: 0 代表公開訊息，1 代表好友限定。
	* MESSAGE: text type 儲存訊息。
	* POSTID: 標記該訊息的流水號，自動增加且為獨一無二。
	* MASTERID: 若為 0 代表是訊息，為其他流水號則代表是屬於該流水號訊息的回應。
* FRIEND 儲存朋友間關係
	* MASTER: 當下使用者。
	* SLAVE: 對應的好友。
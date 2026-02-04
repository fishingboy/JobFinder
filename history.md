# 修改歷史

## 2026-02-04

### 升級 PHP 8 / Laravel 10

- **_dockerConf/phpfpm/Dockerfile** - 升級 PHP 版本至 8.x
- **docker-compose.yml** - 調整 Docker 服務設定
- **composer.json** - 更新相依套件版本
- **config/app.php** - 更新 Laravel 10 設定
- **app/Exceptions/Handler.php** - 適配 Laravel 10
- **app/Http/Kernel.php** - 適配 Laravel 10
- **app/Providers/EventServiceProvider.php** - 適配 Laravel 10
- **app/Providers/RouteServiceProvider.php** - 適配 Laravel 10
- **routes/web.php** - 更新路由設定

### 建立可執行的 menu

- **start.ps1** - 建立 Windows PowerShell 啟動選單

### 移除 firephp 相關程式

- **app/Library/debug.php** - 刪除檔案
- **app/Classes/Job104.php** - 移除 Debug 相關引用
- **app/Http/Controllers/CompanyController.php** - 移除 Debug 相關引用
- **app/Http/Controllers/FavoriteController.php** - 移除 Debug 相關引用
- **app/Http/Controllers/JobController.php** - 移除 Debug 相關引用
- **app/Http/Controllers/PluginController.php** - 刪除檔案
- **app/Http/Controllers/UpdateController.php** - 移除 Debug 相關引用
- **app/Models/Company.php** - 移除 Debug 相關引用
- **app/Models/Favorite.php** - 移除 fblog 相關程式
- **app/Models/Job.php** - 移除 fblog 相關程式
- **routes/web.php** - 移除 plugin 路由

### 修正測試檔案

- **tests/Classes/GetConditionsTest.php** - namespace 從 `Classes` 改為 `Tests\Classes`，繼承 `Tests\TestCase`
- **tests/Classes/Get104CompanyTest.php** - namespace 從 `Classes` 改為 `Tests\Classes`
- **tests/ExampleTest.php** - 加入 namespace `Tests`，移除舊版 Laravel 的 `visit()` 方法改用 `get()` + `assertSee()`
- **tests/Library/Capital2NumberTest.php** - namespace 從 `Library` 改為 `Tests\Library`
- **tests/Models/GetNullEmployeesCompaniesTest.php** - namespace 從 `Models` 改為 `Tests\Models`，繼承 `Tests\TestCase`，修正斷言邏輯
- **tests/CreatesApplication.php** - 新增此 trait 檔案（Laravel 10 需要）
- **composer.json** - `autoload-dev` 從 classmap 改為 psr-4
- **phpunit.xml** - 更新為 PHPUnit 9.x 格式

### 重構 Job104 類別

- **app/Classes/Job104.php**
  - 新增 `getJobs($conditions)` 方法，從 104 API 取得工作資料
  - 修改 `update()` 方法改用 `getJobs()` 取得資料
  - 修正 `JSON_DIR` 常數重複定義問題
- **tests/Classes/Job104GetJobsTest.php** - 新增測試檔案，包含 4 個測試案例

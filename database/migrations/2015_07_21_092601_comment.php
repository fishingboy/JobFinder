<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Comment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company', function ($table)
        {
            $table->string('c_code','200')->comment('104 公司代碼')->change();
            $table->string('name','200')->comment('公司名稱')->change();
            $table->string('addr_no_descript','200')->comment('公司地區類目描述, ex:台北市中正區')->change();
            $table->string('address','200')->comment('公司地址,ex:寶中路119-1號10樓')->change();
            $table->string('addr_indzone','200')->comment('公司所在,ex:新竹科學工業園區')->change();
            $table->string('indcat','200')->comment('公司產業別')->change();
            $table->string('link','200')->comment('公司網頁連結')->change();
            $table->text('product')->comment('公司主要產品')->change();
            $table->text('profile')->comment('公司簡介')->change();
            $table->text('welfare')->comment('公司福利制度')->change();
        });

        Schema::table('job', function ($table)
        {
            $table->integer('companyID')->comment('公司流水號')->change();
            $table->string('title','200')->comment('職務名稱')->change();
            $table->string('j_code','200')->comment('104 工作代碼')->change();
            $table->string('job_addr_no_descript','200')->comment('職務工作地區, ex:台北縣新店市')->change();
            $table->string('job_address','200')->comment('職務工作地點, ex:寶中路119-1號10樓')->change();
            $table->string('jobcat_descript','200')->comment('職務類別 以@分隔')->change();
            $table->string('description','200')->comment('職務說明, ex:系統分析/程式設計')->change();
            $table->integer('period')->comment('工作年資')->change();
            $table->string('appear_date','200')->comment('職務更新日期 格式為YYYYMMDD (年月日)')->change();
            $table->integer('dis_role')->comment('身障類型1')->change();
            $table->integer('dis_level')->comment('身障程度1')->change();
            $table->integer('dis_role2')->comment('身障類型2')->change();
            $table->integer('dis_level2')->comment('身障程度2')->change();
            $table->integer('dis_role3')->comment('身障類型3')->change();
            $table->integer('dis_level3')->comment('身障程度3')->change();
            $table->string('driver','200')->comment('具備駕照 2進位')->change();
            $table->string('handicompendium','200')->comment('是否持有身障手冊 0=無 1=有')->change();
            $table->string('role','200')->comment('身份類別')->change();
            $table->string('role_status','200')->comment('求職者身份類別')->change();
            $table->string('s2','200')->comment('管理責任')->change();
            $table->string('s3','200')->comment('是否出差')->change();
            $table->string('sal_month_low','200')->comment('最低薪資 (月薪)')->change();
            $table->string('sal_month_high','200')->comment('最高薪資 (月薪)')->change();
            $table->string('worktime','200')->comment('職務休假')->change();
            $table->string('startby','200')->comment('最快可上班日')->change();
            $table->string('cert_all_descript','200')->comment('證照類目描述 以空白分隔')->change();
            $table->string('jobskill_all_desc','200')->comment('工作技能')->change();
            $table->string('pcskill_all_desc','200')->comment('電腦技能描述 ')->change();
            $table->string('language1','200')->comment('外語條件1')->change();
            $table->string('language2','200')->comment('外語條件2')->change();
            $table->string('language3','200')->comment('外語條件3')->change();
            $table->string('lat','200')->comment('緯度')->change();
            $table->string('lon','200')->comment('經度')->change();
            $table->string('major_cat_descript','200')->comment('科系編號中文描述, ex:資訊工程相關@類目@類目 (以@分隔)')->change();
            $table->string('minbinary_edu','200')->comment('最低學歷要求')->change();
            $table->string('need_emp','200')->comment('最低需求人數')->change();
            $table->string('need_emp1','200')->comment('最高需求人數')->change();
            $table->string('ondutytime','200')->comment('上班時間, ex:0900')->change();
            $table->string('offduty_time','200')->comment('下班時間, ex:1800')->change();
            $table->string('others','200')->comment('其他條件')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company', function ($table)
        {
            $table->string('c_code','200')->comment('')->change();
            $table->string('name','200')->comment('')->change();
            $table->string('addr_no_descript','200')->comment('')->change();
            $table->string('address','200')->comment('')->change();
            $table->string('addr_indzone','200')->comment('')->change();
            $table->string('indcat','200')->comment('')->change();
            $table->string('link','200')->comment('')->change();
            $table->text('product')->comment('')->change();
            $table->text('profile')->comment('')->change();
            $table->text('welfare')->comment('')->change();
        });

        Schema::table('job', function ($table)
        {
            $table->integer('companyID')->comment('')->change();
            $table->string('title','200')->comment('')->change();
            $table->string('j_code','200')->comment('')->change();
            $table->string('job_addr_no_descript','200')->comment('')->change();
            $table->string('job_address','200')->comment('')->change();
            $table->string('jobcat_descript','200')->comment('')->change();
            $table->string('description','200')->comment('')->change();
            $table->integer('period')->comment('')->change();
            $table->string('appear_date','200')->comment('')->change();
            $table->integer('dis_role')->comment('')->change();
            $table->integer('dis_level')->comment('')->change();
            $table->integer('dis_role2')->comment('')->change();
            $table->integer('dis_level2')->comment('')->change();
            $table->integer('dis_role3')->comment('')->change();
            $table->integer('dis_level3')->comment('')->change();
            $table->string('driver','200')->comment('')->change();
            $table->string('handicompendium','200')->comment('')->change();
            $table->string('role','200')->comment('')->change();
            $table->string('role_status','200')->comment('')->change();
            $table->string('s2','200')->comment('')->change();
            $table->string('s3','200')->comment('')->change();
            $table->string('sal_month_low','200')->comment('')->change();
            $table->string('sal_month_high','200')->comment('')->change();
            $table->string('worktime','200')->comment('')->change();
            $table->string('startby','200')->comment('')->change();
            $table->string('cert_all_descript','200')->comment('')->change();
            $table->string('jobskill_all_desc','200')->comment('')->change();
            $table->string('pcskill_all_desc','200')->comment('')->change();
            $table->string('language1','200')->comment('')->change();
            $table->string('language2','200')->comment('')->change();
            $table->string('language3','200')->comment('')->change();
            $table->string('lat','200')->comment('')->change();
            $table->string('lon','200')->comment('')->change();
            $table->string('major_cat_descript','200')->comment('')->change();
            $table->string('minbinary_edu','200')->comment('')->change();
            $table->string('need_emp','200')->comment('')->change();
            $table->string('need_emp1','200')->comment('')->change();
            $table->string('ondutytime','200')->comment('')->change();
            $table->string('offduty_time','200')->comment('')->change();
            $table->string('others','200')->comment('')->change();
        });
    }
}

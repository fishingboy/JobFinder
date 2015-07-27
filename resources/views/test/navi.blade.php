@if ($controller != 'App\Http\Controllers\FavoriteController')
{{-- 排序 --}}
    排序:
    @if ($controller == 'App\Http\Controllers\CompanyController')
    <a href='{{ $url['job_count_desc_url'] }}'>職缺數 ▼</a> |
    <a href='{{ $url['job_count_asc_url'] }}'>職缺數 ▲</a> |
    @endif

    @if ($controller == 'App\Http\Controllers\JobController')
    <a href='{{ $url['pay_low_desc_url'] }}'>最低薪資 ▼</a> |
    <a href='{{ $url['pay_low_asc_url'] }}'>最低薪資 ▲</a> |
    <a href='{{ $url['pay_high_desc_url'] }}'>最高薪資 ▼</a> |
    <a href='{{ $url['pay_high_asc_url'] }}'>最高薪資 ▲</a> |
    @endif

    <a href='{{ $url['employees_desc_url'] }}'>員工人數 ▼</a> |
    <a href='{{ $url['employees_asc_url'] }}'>員工人數 ▲</a> |
    <a href='{{ $url['capital_desc_url'] }}'>資本額 ▼</a> |
    <a href='{{ $url['capital_asc_url'] }}'>資本額 ▲</a> |
    <a href='{{ $url['time_desc_url'] }}'>同步時間 ▼</a> |
    <a href='{{ $url['time_asc_url'] }}'>同步時間 ▲</a>

    {{-- 分頁 --}}
    分頁:
    <a href='{{ $url['prev_url'] }}'>上一頁 ▲</a> |
    <a href='{{ $url['next_url'] }}'>下一頁 ▼</a>
@endif

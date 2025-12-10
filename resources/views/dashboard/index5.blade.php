@extends('layout.layout')

@section('content')
<div class="row gy-4">

    {{-- Stats Cards --}}
    <div class="col-xxl-3 col-sm-6">
        <div class="card px-24 py-16 shadow-none radius-8 border h-100 bg-gradient-start-3">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                    <div class="d-flex align-items-center">
                        <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                            <span class="mb-0 w-40-px h-40-px bg-primary-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                <iconify-icon icon="fa6-solid:money-bill-trend-up" class="icon"></iconify-icon>
                            </span>
                        </div>
                        <div>
                            <span class="mb-2 fw-medium text-secondary-light text-md">Total Salary (6M)</span>
                            <h6 class="fw-semibold my-1">₹{{ number_format($salarySummary->sum('total_salary'), 2) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6">
        <div class="card px-24 py-16 shadow-none radius-8 border h-100 bg-gradient-start-2">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                    <div class="d-flex align-items-center">
                        <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                            <span class="mb-0 w-40-px h-40-px bg-success-main flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                <iconify-icon icon="fa6-solid:user-check" class="text-white text-2xl mb-0"></iconify-icon>
                            </span>
                        </div>
                        <div>
                            <span class="mb-2 fw-medium text-secondary-light text-md">Present Today</span>
                            <h6 class="fw-semibold my-1">{{ $attendanceSummary->present ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6">
        <div class="card px-24 py-16 shadow-none radius-8 border h-100 bg-gradient-start-5">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                    <div class="d-flex align-items-center">
                        <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                            <span class="mb-0 w-40-px h-40-px bg-danger-main flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                <iconify-icon icon="fa6-solid:user-xmark" class="text-white text-2xl mb-0"></iconify-icon>
                            </span>
                        </div>
                        <div>
                            <span class="mb-2 fw-medium text-secondary-light text-md">Absent Today</span>
                            <h6 class="fw-semibold my-1">{{ $attendanceSummary->absent ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6">
        <div class="card px-24 py-16 shadow-none radius-8 border h-100 bg-gradient-start-4">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                    <div class="d-flex align-items-center">
                        <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                            <span class="mb-0 w-40-px h-40-px bg-warning-main flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                <iconify-icon icon="fa6-solid:user-clock" class="text-white text-2xl mb-0"></iconify-icon>
                            </span>
                        </div>
                        <div>
                            <span class="mb-2 fw-medium text-secondary-light text-md">Half Day Today</span>
                            <h6 class="fw-semibold my-1">{{ $attendanceSummary->halfday ?? 0 }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Salary Chart --}}
    <div class="col-xxl-6 col-sm-12">
        <div class="card px-24 py-16 shadow-none radius-8 border h-100">
            <h5 class="fw-bold mb-3">Salary Paid (Last 6 Months)</h5>
            <div id="salaryChart" style="height: 300px;"></div>
        </div>
    </div>

    {{-- Attendance Chart --}}
    <div class="col-xxl-6 col-sm-12">
        <div class="card px-24 py-16 shadow-none radius-8 border h-100">
            <h5 class="fw-bold mb-3">Today's Attendance</h5>
            <div id="attendanceChart" style="height: 300px;"></div>
        </div>
    </div>

    {{-- Revenue vs Expense --}}
    <div class="col-xxl-12 col-sm-12">
        <div class="card px-24 py-16 shadow-none radius-8 border h-100">
            <h5 class="fw-bold mb-3">Revenue vs Expense</h5>
            <div id="revExpChart" style="height: 350px;"></div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('js/apexcharts.min.js') }}"></script>
<script>
var salaryChart = new ApexCharts(document.querySelector("#salaryChart"), {
    chart: { type: 'line', height: 280 },
    series: [{
        name: "Salary Paid",
        data: @json($salarySummary->pluck('total_salary'))
    }],
    xaxis: { categories: @json($salarySummary->pluck('month')) }
});
salaryChart.render();

var attendanceChart = new ApexCharts(document.querySelector("#attendanceChart"), {
    chart: { type: 'pie', height: 280 },
    series: [
        {{ $attendanceSummary->present ?? 0 }},
        {{ $attendanceSummary->absent ?? 0 }},
        {{ $attendanceSummary->halfday ?? 0 }}
    ],
    labels: ['Present', 'Absent', 'Half Day']
});
attendanceChart.render();

var revExp = new ApexCharts(document.querySelector("#revExpChart"), {
    chart: { type: 'bar', height: 300 },
    series: [{
        name: 'Revenue',
        data: @json($revenue)
    }, {
        name: 'Expense',
        data: @json($expense)
    }],
    xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'] }
});
revExp.render();
</script>
@endsection

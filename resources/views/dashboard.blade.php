<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p>
                    <span class="italic inline-block mr-8 text-sm">
                        Fuel consumption
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd"
                           d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                           clip-rule="evenodd"/>
                        </svg>
                    </span>
                    Today
                    <x-badge :content="$currentConsumption['currentDayConsumption']" type="info"/>
                </p>
                <p>This Month
                    <x-badge :content="$currentConsumption['currentMonthConsumption']" type="info"/>
                </p>
                <p>This Year
                    <x-badge :content="$currentConsumption['currentYearConsumption']" type="info"/>
                </p>
            </div>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id='main-wrapper' class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <p class="text-center text-blue-500 text-lg">Summary</p>

                <div class="my-2 flex justify-around h-[15rem] p-2 mb-4">
                    <x-list class="w-1/3 p-2 overflow-y-scroll shadow-lg">
                        <p class="text-center font-medium underline text-blue-400">Yearly
                            <sm>(lr)</sm>
                        </p>
                        @foreach($yearlyConsumption as $yc)
                            <x-list-item class="flex justify-between">
                                <span>{{$yc->date}}</span> <span>{{number_format($yc->consumption, 2)}}</span>
                            </x-list-item>
                        @endforeach
                    </x-list>

                    <x-list class="w-1/3 p-2 overflow-y-scroll shadow-lg">
                        <p class="text-center font-medium underline text-blue-400">Monthly
                            <sm>(lr)</sm>
                        </p>
                        @foreach($monthlyConsumption as $mc)
                            <x-list-item class="flex justify-between">
                                <span>{{$mc->date}}</span> <span>{{number_format($mc->consumption, 2)}}</span>
                            </x-list-item>
                        @endforeach
                    </x-list>

                    <x-list class="w-1/3 p-2 overflow-y-scroll shadow-lg">
                        <p class="text-center font-medium underline text-blue-400">Weekly
                            <sm>(lr)</sm>
                        </p>
                        @foreach($weeklyConsumption as $wc)
                            <x-list-item class="flex justify-between">
                                <span>{{ \App\Helpers\DateHelpers::getFormattedYearFromWeekNumber($wc->date).'(w)' .' (' . $wc->date}} w)</span>
                                <span>{{number_format($wc->consumption, 2)}}</span>
                            </x-list-item>
                        @endforeach
                    </x-list>

                </div>
                <div id="filters-wrapper" class="flex bg-slate-200 justify-between items-center mb-2 -b-4">
                    <div id="total-consumption-wrapper">
                        Total cosumption
                        <x-badge type="info">{{number_format($totalConsumption, 2)}}</x-badge>
                    </div>
                    <div id="date-range-picker" class="mb-2 mx-1">
                        <x-jet-label value="Pick a date range"/>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 inline-block" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <x-jet-input class="w-56"/>
                    </div>
                </div>
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            $(function () {
                const ctx = $('#myChart');
                let mainWrapperElm = $('#main-wrapper');

                const datePickerElm = $('#date-range-picker input');
                let selectedStartDate;
                let selectedEndDate;

                $(document).ajaxStart(function () {
                    mainWrapperElm.append(`<x-spinner/>`)
                }).ajaxStop(function () {
                    $('#spinner').remove()
                }).ajaxError(function () {
                    mainWrapperElm.prepend(`<x-alert content='Error occurred please try again shortly' type='danger'/>`)
                })


                // Get total fuel consumption
                function getTotalFuelConsumptionFromServer(startDate, endDate) {

                    let url = '{{route('api.admin.dashboard.getTotalFuelConsumption')}}';

                    if (startDate && endDate) {
                        const params = new URLSearchParams({
                            start_date: startDate,
                            end_date: endDate,
                        });

                        url += '?' + params.toString();
                    }
                    $.ajax({
                        url,
                        headers: {
                            accept: 'application/json'
                        },
                        success: function (data) {
                            $('#total-consumption-wrapper span').text(data)
                            // return data.totalFuelConsumption
                            //initFuelConsumptionChart(data.dates, data.consumptions)
                        }
                    })
                };

                /**
                 * Implement chart for the fuel consumption report
                 * @param startDate
                 * @param endDate
                 */
                function getFuelConsumptionReportFromServer(startDate, endDate) {

                    let url = '{{route('api.admin.dashboard.getFuelConsumptionReport')}}';

                    if (startDate && endDate) {
                        const params = new URLSearchParams({
                            start_date: startDate,
                            end_date: endDate,
                        });

                        url += '?' + params.toString();
                    }
                    $.ajax({
                        url,
                        headers: {
                            accept: 'application/json'
                        },
                        success: function (data) {
                            //return {dates: date.dates, consumptions: data.consumptions}
                            initFuelConsumptionChart(data.dates, data.consumptions)
                        }
                    })
                };

                function initFuelConsumptionChart(dates, consumptions) {
                    myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: dates,
                            datasets: [{
                                label: 'Sum of consumptions',
                                data: consumptions,
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                getFuelConsumptionReportFromServer();

                /**
                 *
                 * end of chart implementation
                 */


                /**
                 * Date range picker functionality
                 */
                datePickerElm.daterangepicker({
                    autoUpdateInput: false,

                });
                datePickerElm.on('apply.daterangepicker', function (ev, picker) {
                    selectedStartDate = picker.startDate.format('YYYY-MM-DD');
                    selectedEndDate = picker.endDate.format('YYYY-MM-DD');

                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));

                    myChart.destroy(); // existing chart should delete to generate new chart

                    // reload the chart accordingly  selected date ranges
                    getFuelConsumptionReportFromServer(selectedStartDate, selectedEndDate);
                    getTotalFuelConsumptionFromServer(selectedStartDate, selectedEndDate);
                });
                /**
                 * end of date range picker functionality
                 */

            })


        </script>
    @endpush
</x-app-layout>


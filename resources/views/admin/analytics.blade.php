@extends('admin.layouts.app')

@section('title', __('admin.analytics'))
@section('page-title', __('admin.analytics'))

@section('content')
    <!-- Statistics Section -->
    <div class="card" style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 32px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
            <h2 style="font-size: 24px; font-weight: 700; color: #1e293b; margin: 0;">{{ __('admin.statistics') }}</h2>
            
            <form method="GET" action="{{ route('admin.analytics') }}" style="display: flex; gap: 16px; align-items: end; flex-wrap: wrap;">
                <!-- Teacher Filter -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">{{ __('admin.select_teacher') }}</label>
                    <select name="teacher_id" id="teacherFilter" style="width: 200px; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                        <option value="">{{ __('admin.all_teachers') }}</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ $selectedTeacherId == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Date From -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">{{ __('admin.from_date') }}</label>
                    <input type="date" name="date_from" value="{{ $dateFrom->format('Y-m-d') }}" 
                           style="padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                </div>
                
                <!-- Date To -->
                <div>
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151; font-size: 14px;">{{ __('admin.to_date') }}</label>
                    <input type="date" name="date_to" value="{{ $dateTo->format('Y-m-d') }}" 
                           style="padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; background: white;">
                </div>
                
                <!-- Submit Button -->
                <div>
                    <button type="submit" style="padding: 12px 24px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; height: fit-content;">
                        <i class="fas fa-filter"></i> {{ __('admin.apply_filters') }}
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Total Hours Display -->
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 32px; color: white;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <p style="font-size: 14px; font-weight: 600; margin: 0 0 8px 0; opacity: 0.9;">{{ __('admin.total_teaching_hours') }}</p>
                    <h3 style="font-size: 48px; font-weight: 700; margin: 0;">{{ number_format($totalHours, 2) }}</h3>
                </div>
                <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clock" style="font-size: 40px;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px; margin-bottom: 32px;">
        <!-- Line Chart: Total Hours Across Months -->
        <div class="card" style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0 0 20px 0;">{{ __('admin.total_hours_across_months') }}</h3>
            <canvas id="hoursLineChart" style="max-height: 300px;"></canvas>
        </div>

        <!-- Bar Chart: Top 5 Teachers by Hours -->
        <div class="card" style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0 0 20px 0;">{{ __('admin.top_teachers_by_hours') }}</h3>
            <canvas id="topTeachersBarChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <!-- Donut Chart: Best Days by Number of Courses -->
    <div class="card" style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 32px;">
        <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0 0 20px 0;">{{ __('admin.best_days_by_courses') }}</h3>
        <div style="display: flex; align-items: center; gap: 40px; flex-wrap: wrap;">
            <div style="flex: 0 0 300px;">
                <canvas id="coursesDonutChart"></canvas>
            </div>
            <div id="donutLegend" style="flex: 1; min-width: 200px;"></div>
        </div>
    </div>

    <!-- Financial Statistics Table -->
    <div class="card" style="background: white; border-radius: 16px; padding: 0; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="padding: 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
            <h3 style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 0;">{{ __('admin.financial_statistics') }}</h3>
            <p style="color: #64748b; font-size: 14px; margin: 8px 0 0 0;">{{ __('admin.calculate_net_profit') }}</p>
        </div>

        <div style="overflow-x: auto; overflow-y: visible; width: 100%; -webkit-overflow-scrolling: touch;">
            <table style="width: 100%; border-collapse: collapse; white-space: nowrap; table-layout: auto;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 16px; text-align: left; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">{{ __('admin.name') }}</th>
                        <th style="padding: 16px; text-align: right; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">{{ __('admin.revenue') }}</th>
                        <th style="padding: 16px; text-align: right; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">{{ __('admin.total_hours') }}</th>
                        <th style="padding: 16px; text-align: right; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">{{ __('admin.expenses') }}</th>
                        <th style="padding: 16px; text-align: right; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">{{ __('admin.advertisements') }}</th>
                        <th style="padding: 16px; text-align: right; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">{{ __('admin.teacher_rates') }}</th>
                        <th style="padding: 16px; text-align: right; font-weight: 600; color: #64748b; font-size: 12px; text-transform: uppercase; white-space: nowrap;">{{ __('admin.net_profit') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($financialStats as $stat)
                        <tr style="border-bottom: 1px solid #f1f5f9;" data-teacher-id="{{ $stat['teacher_id'] }}">
                            <td style="padding: 16px; white-space: nowrap; font-weight: 600; color: #1e293b;">{{ $stat['name'] }}</td>
                            <td style="padding: 16px; white-space: nowrap; text-align: right; color: #64748b;" class="income-cell" data-income="{{ $stat['income'] }}">
                                ${{ number_format($stat['income'], 2) }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap; text-align: right; color: #64748b;" class="hours-cell" data-hours="{{ $stat['hours'] }}">
                                {{ number_format($stat['hours'], 2) }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap; text-align: right;">
                                <input type="number" 
                                       step="0.01" 
                                       min="0" 
                                       class="expense-input" 
                                       data-teacher-id="{{ $stat['teacher_id'] }}"
                                       style="width: 120px; padding: 8px 12px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 14px; text-align: right;"
                                       placeholder="0.00">
                            </td>
                            <td style="padding: 16px; white-space: nowrap; text-align: right;">
                                <input type="number" 
                                       step="0.01" 
                                       min="0" 
                                       class="advertisement-input" 
                                       data-teacher-id="{{ $stat['teacher_id'] }}"
                                       style="width: 120px; padding: 8px 12px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 14px; text-align: right;"
                                       placeholder="0.00">
                            </td>
                            <td style="padding: 16px; white-space: nowrap; text-align: right; color: #64748b;" class="teacher-rate-cell" data-rate="{{ $stat['teacher_rate_cost'] }}">
                                ${{ number_format($stat['teacher_rate_cost'], 2) }}
                            </td>
                            <td style="padding: 16px; white-space: nowrap; text-align: right; font-weight: 700; color: #1e293b;" class="net-profit-cell">
                                $<span class="net-profit-value">{{ number_format($stat['income'] - $stat['teacher_rate_cost'], 2) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 40px; text-align: center; color: #64748b;">
                                <i class="fas fa-chart-line" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                                <p>No financial data available</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr style="background: #f8fafc; border-top: 2px solid #e2e8f0;">
                        <td style="padding: 16px; font-weight: 700; color: #1e293b;">Total Academy Net Profit</td>
                        <td style="padding: 16px; text-align: right; font-weight: 700; color: #1e293b;" class="total-income">
                            $<span id="totalIncomeValue">0.00</span>
                        </td>
                        <td style="padding: 16px; text-align: right; font-weight: 700; color: #1e293b;" class="total-hours">
                            <span id="totalHoursValue">0.00</span>
                        </td>
                        <td style="padding: 16px; text-align: right; font-weight: 700; color: #1e293b;" class="total-expenses">
                            $<span id="totalExpensesValue">0.00</span>
                        </td>
                        <td style="padding: 16px; text-align: right; font-weight: 700; color: #1e293b;" class="total-advertisements">
                            $<span id="totalAdvertisementsValue">0.00</span>
                        </td>
                        <td style="padding: 16px; text-align: right; font-weight: 700; color: #1e293b;" class="total-teacher-rate">
                            $<span id="totalTeacherRateValue">0.00</span>
                        </td>
                        <td style="padding: 16px; text-align: right; font-weight: 700; color: #10b981; font-size: 18px;" class="total-net-profit">
                            $<span id="totalNetProfitValue">0.00</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Chart data from backend
        const hoursByMonthData = @json($hoursByMonth);
        const topTeachersData = @json($topTeachers);
        const coursesByDayData = @json($coursesByDay);
        const financialStatsData = @json($financialStats);

        // Initialize Line Chart: Total Hours Across Months
        const hoursLineCtx = document.getElementById('hoursLineChart');
        if (hoursLineCtx) {
            const hoursLabels = Object.keys(hoursByMonthData);
            const hoursValues = Object.values(hoursByMonthData);
            
            new Chart(hoursLineCtx, {
                type: 'line',
                data: {
                    labels: hoursLabels,
                    datasets: [{
                        label: 'Total Hours',
                        data: hoursValues,
                        borderColor: '#fbbf24',
                        backgroundColor: 'rgba(251, 191, 36, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#fbbf24',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            callbacks: {
                                label: function(context) {
                                    return 'Total Hours: ' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toFixed(0) + 'h';
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Initialize Bar Chart: Top 5 Teachers by Hours
        const topTeachersCtx = document.getElementById('topTeachersBarChart');
        if (topTeachersCtx) {
            const teacherNames = topTeachersData.map(t => t.name);
            const teacherHours = topTeachersData.map(t => t.hours);
            
            new Chart(topTeachersCtx, {
                type: 'bar',
                data: {
                    labels: teacherNames,
                    datasets: [{
                        label: 'Total Hours',
                        data: teacherHours,
                        backgroundColor: [
                            'rgba(236, 72, 153, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(251, 191, 36, 0.8)',
                            'rgba(20, 184, 166, 0.8)',
                            'rgba(139, 92, 246, 0.8)'
                        ],
                        borderColor: [
                            '#ec4899',
                            '#3b82f6',
                            '#fbbf24',
                            '#14b8a6',
                            '#8b5cf6'
                        ],
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            callbacks: {
                                label: function(context) {
                                    return 'Total Hours: ' + context.parsed.y.toFixed(2) + 'h';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toFixed(0) + 'h';
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Initialize Donut Chart: Best Days by Number of Courses
        const coursesDonutCtx = document.getElementById('coursesDonutChart');
        if (coursesDonutCtx) {
            const dayNames = coursesByDayData.map(d => d.day);
            const courseCounts = coursesByDayData.map(d => d.count);
            
            const dayColors = [
                'rgba(236, 72, 153, 0.8)',   // Sunday - Pink
                'rgba(59, 130, 246, 0.8)',  // Monday - Blue
                'rgba(251, 191, 36, 0.8)',  // Tuesday - Yellow
                'rgba(20, 184, 166, 0.8)',  // Wednesday - Teal
                'rgba(139, 92, 246, 0.8)',   // Thursday - Purple
                'rgba(249, 115, 22, 0.8)',  // Friday - Orange
                'rgba(156, 163, 175, 0.8)'  // Saturday - Gray
            ];

            const donutChart = new Chart(coursesDonutCtx, {
                type: 'doughnut',
                data: {
                    labels: dayNames,
                    datasets: [{
                        data: courseCounts,
                        backgroundColor: dayColors.slice(0, dayNames.length),
                        borderColor: '#fff',
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            callbacks: {
                                label: function(context) {
                                    return 'Courses: ' + context.parsed;
                                }
                            }
                        }
                    }
                }
            });

            // Create custom legend
            const legendContainer = document.getElementById('donutLegend');
            if (legendContainer) {
                dayNames.forEach((day, index) => {
                    const legendItem = document.createElement('div');
                    legendItem.style.cssText = 'display: flex; align-items: center; gap: 12px; margin-bottom: 12px;';
                    legendItem.innerHTML = `
                        <div style="width: 20px; height: 20px; border-radius: 4px; background: ${dayColors[index]};"></div>
                        <span style="color: #64748b; font-size: 14px; font-weight: 500;">${day}</span>
                    `;
                    legendContainer.appendChild(legendItem);
                });
            }
        }

        // Financial Calculations
        function calculateNetProfit() {
            let totalIncome = 0;
            let totalHours = 0;
            let totalExpenses = 0;
            let totalAdvertisements = 0;
            let totalTeacherRate = 0;
            let totalNetProfit = 0;

            document.querySelectorAll('tbody tr').forEach(row => {
                const income = parseFloat(row.querySelector('.income-cell').dataset.income) || 0;
                const hours = parseFloat(row.querySelector('.hours-cell').dataset.hours) || 0;
                const teacherRate = parseFloat(row.querySelector('.teacher-rate-cell').dataset.rate) || 0;
                const expenseInput = row.querySelector('.expense-input');
                const advertisementInput = row.querySelector('.advertisement-input');
                
                const expenses = parseFloat(expenseInput.value) || 0;
                const advertisements = parseFloat(advertisementInput.value) || 0;
                
                // Calculate net profit for this teacher
                const netProfit = income - teacherRate - expenses - advertisements;
                row.querySelector('.net-profit-value').textContent = netProfit.toFixed(2);
                
                // Update cell color based on profit
                const netProfitCell = row.querySelector('.net-profit-cell');
                if (netProfit >= 0) {
                    netProfitCell.style.color = '#10b981';
                } else {
                    netProfitCell.style.color = '#ef4444';
                }

                // Accumulate totals
                totalIncome += income;
                totalHours += hours;
                totalExpenses += expenses;
                totalAdvertisements += advertisements;
                totalTeacherRate += teacherRate;
                totalNetProfit += netProfit;
            });

            // Update totals
            document.getElementById('totalIncomeValue').textContent = totalIncome.toFixed(2);
            document.getElementById('totalHoursValue').textContent = totalHours.toFixed(2);
            document.getElementById('totalExpensesValue').textContent = totalExpenses.toFixed(2);
            document.getElementById('totalAdvertisementsValue').textContent = totalAdvertisements.toFixed(2);
            document.getElementById('totalTeacherRateValue').textContent = totalTeacherRate.toFixed(2);
            
            const totalNetProfitElement = document.getElementById('totalNetProfitValue');
            totalNetProfitElement.textContent = totalNetProfit.toFixed(2);
            
            // Update color based on total profit
            const totalNetProfitCell = document.querySelector('.total-net-profit');
            if (totalNetProfit >= 0) {
                totalNetProfitCell.style.color = '#10b981';
            } else {
                totalNetProfitCell.style.color = '#ef4444';
            }
        }

        // Attach event listeners to expense and advertisement inputs
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.expense-input, .advertisement-input').forEach(input => {
                input.addEventListener('input', calculateNetProfit);
                input.addEventListener('change', calculateNetProfit);
            });
            
            // Initial calculation
            calculateNetProfit();
        });
    </script>
@endsection


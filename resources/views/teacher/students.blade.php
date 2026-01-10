@extends('teacher.layouts.app')

@section('title', __('teacher.my_students'))
@section('page-title', __('teacher.my_students'))

@section('content')
    <!-- Header Actions -->
    <div class="card" style="margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 24px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
                    <i class="fas fa-user-graduate" style="color: #14b8a6; margin-right: 8px;"></i>
                    <span style="display: inline-block; width: 8px; height: 8px; background: #10B981; border-radius: 50%; margin-right: 8px; vertical-align: middle;"></span>
                    {{ __('teacher.active_students') }}
                </h2>
                <p style="color: #64748b; font-size: 14px;">
                    {{ __('teacher.view_manage_students') }}
                </p>
            </div>
        </div>
    </div>

    @if($students->count() > 0)
        <!-- Students Grid Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 24px;">
            @foreach($students as $student)
                <div class="card" style="padding: 0; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;" 
                     onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.1)'" 
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)'">
                    <!-- Circular Graphic with Mosque/Crescent -->
                    <div style="width: 100%; height: 180px; background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%); position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <!-- Birds (small white circles) -->
                        <div style="position: absolute; top: 20px; left: 30px; width: 8px; height: 8px; background: white; border-radius: 50%; opacity: 0.8;"></div>
                        <div style="position: absolute; top: 15px; right: 40px; width: 6px; height: 6px; background: white; border-radius: 50%; opacity: 0.8;"></div>
                        <div style="position: absolute; top: 25px; left: 50%; width: 7px; height: 7px; background: white; border-radius: 50%; opacity: 0.8;"></div>
                        <div style="position: absolute; top: 30px; right: 25px; width: 9px; height: 9px; background: white; border-radius: 50%; opacity: 0.8;"></div>
                        
                        <!-- Mosque Silhouette -->
                        <div style="position: relative; width: 120px; height: 100px;">
                            <!-- Minarets -->
                            <div style="position: absolute; left: 10px; bottom: 0; width: 12px; height: 60px; background: white; border-radius: 2px 2px 0 0;"></div>
                            <div style="position: absolute; right: 10px; bottom: 0; width: 12px; height: 60px; background: white; border-radius: 2px 2px 0 0;"></div>
                            <div style="position: absolute; left: 50%; transform: translateX(-50%); bottom: 0; width: 15px; height: 70px; background: white; border-radius: 2px 2px 0 0;"></div>
                            
                            <!-- Dome -->
                            <div style="position: absolute; left: 50%; transform: translateX(-50%); bottom: 40px; width: 60px; height: 50px; background: white; border-radius: 50% 50% 0 0;"></div>
                            
                            <!-- Crescent Moon -->
                            <div style="position: absolute; left: 50%; top: 0; transform: translateX(-50%); width: 40px; height: 40px; border: 4px solid white; border-radius: 0 0 50% 50%; border-top: none; border-left: none;"></div>
                        </div>
                    </div>
                    
                    <!-- Student Information -->
                    <div style="padding: 20px; text-align: center;">
                        <!-- Student Name -->
                        <h3 style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 16px;">
                            {{ $student->name }}
                        </h3>
                        
                        <!-- Payment Status Badge -->
                        @php
                            $paymentStatus = $student->paymentStatus;
                            $statusName = $paymentStatus ? $paymentStatus->name : 'EN ATTENTE DE PAYEMENT';
                            $statusColor = $paymentStatus ? $paymentStatus->color : '#F59E0B';
                        @endphp
                        <div style="margin-bottom: 16px;">
                            <span style="display: inline-block; padding: 8px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; background: {{ $statusColor }}; color: white;">
                                {{ $statusName }}
                            </span>
                        </div>
                        
                        <!-- Package Number -->
                        <p style="font-size: 14px; color: #64748b; margin: 0;">
                            <strong style="color: #1e293b;">{{ __('teacher.package_number') }}:</strong> {{ $student->package_number }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card" style="text-align: center; padding: 60px 20px; color: #94a3b8;">
            <i class="fas fa-user-slash" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
            <p style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">{{ __('teacher.no_students_assigned') }}</p>
            <p style="font-size: 14px; margin-bottom: 24px;">{{ __('teacher.students_will_appear') }}</p>
        </div>
    @endif
@endsection

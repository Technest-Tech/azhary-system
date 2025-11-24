@extends('admin.layouts.app')

@section('title', 'Add New Teacher')
@section('page-title', 'Add New Teacher')

@section('content')
    <div class="card">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
            <a href="{{ route('admin.teachers') }}" style="width: 40px; height: 40px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #64748b; text-decoration: none;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 style="font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
                    <i class="fas fa-user-plus" style="color: #667eea; margin-right: 8px;"></i>
                    Add New Teacher
                </h2>
                <p style="color: #64748b; font-size: 14px;">
                    Fill in the information below to add a new teacher
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.teachers.store') }}">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Name -->
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Full Name <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.3s;"
                        onfocus="this.style.borderColor='#667eea'; this.style.outline='none';"
                        onblur="this.style.borderColor='#e2e8f0';"
                        placeholder="Enter full name">
                    @error('name')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Email Address <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.3s;"
                        onfocus="this.style.borderColor='#667eea'; this.style.outline='none';"
                        onblur="this.style.borderColor='#e2e8f0';"
                        placeholder="teacher@example.com">
                    @error('email')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Phone Number <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.3s;"
                        onfocus="this.style.borderColor='#667eea'; this.style.outline='none';"
                        onblur="this.style.borderColor='#e2e8f0';"
                        placeholder="+1234567890">
                    @error('phone')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Date of Birth -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Date of Birth <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.3s;"
                        onfocus="this.style.borderColor='#667eea'; this.style.outline='none';"
                        onblur="this.style.borderColor='#e2e8f0';">
                    @error('date_of_birth')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Currency -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Currency <span style="color: #dc2626;">*</span>
                    </label>
                    <select name="currency" required
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.3s;"
                        onfocus="this.style.borderColor='#667eea'; this.style.outline='none';"
                        onblur="this.style.borderColor='#e2e8f0';">
                        <option value="">Select Currency</option>
                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                        <option value="GBP" {{ old('currency') == 'GBP' ? 'selected' : '' }}>GBP</option>
                        <option value="EGP" {{ old('currency') == 'EGP' ? 'selected' : '' }}>EGP</option>
                        <option value="SAR" {{ old('currency') == 'SAR' ? 'selected' : '' }}>SAR</option>
                    </select>
                    @error('currency')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Hourly Rate -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Hourly Rate <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="number" name="hourly_rate" value="{{ old('hourly_rate', '0.00') }}" required min="0" step="0.01"
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.3s;"
                        onfocus="this.style.borderColor='#667eea'; this.style.outline='none';"
                        onblur="this.style.borderColor='#e2e8f0';"
                        placeholder="0.00">
                    @error('hourly_rate')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label style="display: block; font-weight: 600; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Password <span style="color: #dc2626;">*</span>
                    </label>
                    <input type="password" name="password" required
                        style="width: 100%; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: all 0.3s;"
                        onfocus="this.style.borderColor='#667eea'; this.style.outline='none';"
                        onblur="this.style.borderColor='#e2e8f0';"
                        placeholder="Enter password (min. 8 characters)">
                    @error('password')
                        <span style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Create Teacher
                </button>
                <a href="{{ route('admin.teachers') }}" class="btn" style="background: #f1f5f9; color: #64748b; text-decoration: none;">
                    <i class="fas fa-times"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection


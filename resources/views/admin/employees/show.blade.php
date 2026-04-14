@extends('layouts.admin')
@section('page-title', 'Employee Detail')

@section('content')

{{-- Top Buttons --}}
<div class="d-flex justify-content-between mb-4">
    <a href="{{ route('admin.employees.index') }}"
       style="color:#f0c040; text-decoration:none; font-size:1.1rem;">← Back</a>
    <a href="{{ route('admin.employees.edit', $employee->id) }}"
       class="btn btn-gold">✏️ Edit Employee</a>
</div>

<div class="row">

    {{-- LEFT: Basic Info --}}
    <div class="col-md-4">
        <div class="card-dark h-100">

            {{-- Photo --}}
            <div class="text-center mb-4">
                @if($employee->photo)
                    <img src="{{ asset('storage/' . $employee->photo) }}"
                         class="rounded-circle"
                         style="width:90px;height:90px;object-fit:cover;border:3px solid #f0c040;">
                @else
                    <div class="rounded-circle bg-warning text-dark d-flex align-items-center
                                justify-content-center mx-auto"
                         style="width:90px;height:90px;font-size:2.5rem;font-weight:bold;">
                        {{ strtoupper(substr($employee->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            {{-- Basic Fields --}}
            <table class="w-100">
                <tr>
                    <td class="pb-3 pe-2">
                        <small style="color:#f0c040">Name-</small>
                        <p class="mb-0 text-white">{{ $employee->name ?? '-' }}</p>
                    </td>
                    <td class="pb-3">
                        <small style="color:#f0c040">Address-</small>
                        <p class="mb-0 text-white">{{ $employee->address ?? '-' }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="pb-3 pe-2">
                        <small style="color:#f0c040">Email Address-</small>
                        <p class="mb-0 text-white">{{ $employee->email ?? '-' }}</p>
                    </td>
                    <td class="pb-3">
                        <small style="color:#f0c040">Pincode-</small>
                        <p class="mb-0 text-white">{{ $employee->pincode ?? '-' }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="pb-3 pe-2">
                        <small style="color:#f0c040">Contact Number-</small>
                        <p class="mb-0 text-white">{{ $employee->contact ?? '-' }}</p>
                    </td>
                    <td class="pb-3">
                        <small style="color:#f0c040">Date of Birth-</small>
                        <p class="mb-0 text-white">{{ $employee->date_of_birth ?? '-' }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="pb-3 pe-2">
                        <small style="color:#f0c040">Blood Group-</small>
                        <p class="mb-0 text-white">{{ $employee->blood_group ?? '-' }}</p>
                    </td>
                    <td class="pb-3">
                        <small style="color:#f0c040">State-</small>
                        <p class="mb-0 text-white">{{ $employee->state ?? '-' }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="pb-3 pe-2">
                        <small style="color:#f0c040">Marital Status-</small>
                        <p class="mb-0 text-white">{{ $employee->marital_status ?? '-' }}</p>
                    </td>
                    <td class="pb-3">
                        <small style="color:#f0c040">City-</small>
                        <p class="mb-0 text-white">{{ $employee->city ?? '-' }}</p>
                    </td>
                </tr>
                <tr>
                    <td class="pb-3 pe-2">
                        <small style="color:#f0c040">Status-</small>
                        <p class="mb-0">
                            <span class="badge {{ $employee->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </p>
                    </td>
                    <td class="pb-3">
                        <small style="color:#f0c040">Joined-</small>
                        <p class="mb-0 text-white">{{ $employee->created_at->format('d/m/Y') }}</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- RIGHT: Timeline --}}
    <div class="col-md-8">
        <div style="position:relative; padding-left:40px;">

            {{-- Vertical Line --}}
            <div style="position:absolute;left:15px;top:0;bottom:0;width:3px;background:#333;"></div>

            {{-- ===== EDUCATION QUALIFICATION ===== --}}
            <div style="position:relative; margin-bottom:35px;">
                <div style="position:absolute;left:-32px;top:0;width:30px;height:30px;
                            border-radius:50%;background:#f0c040;display:flex;
                            align-items:center;justify-content:center;">
                    <i class="fas fa-check" style="color:#000;font-size:0.8rem;"></i>
                </div>
                <h5 style="color:#f0c040" class="mb-3">Education Qualification</h5>

                @forelse($employee->qualifications as $qual)
                <div class="card-dark mb-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <small style="color:#f0c040">Degree-</small>
                            <p class="mb-0 text-white">{{ $qual->qualification_type ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">Institution-</small>
                            <p class="mb-0 text-white">{{ $qual->institution_name ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">Field of Study-</small>
                            <p class="mb-0 text-white">{{ $qual->field_of_study ?? '-' }}</p>
                        </div>
                        
                        <div class="col-md-3">
                            <small style="color:#f0c040">Start Date-</small>
                            <p class="mb-0 text-white">{{ $qual->start_date ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">End Date-</small>
                            <p class="mb-0 text-white">{{ $qual->end_date ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted">No qualifications added.</p>
                @endforelse
            </div>

            {{-- ===== PREVIOUS EMPLOYER ===== --}}
            <div style="position:relative; margin-bottom:35px;">
                <div style="position:absolute;left:-32px;top:0;width:30px;height:30px;
                            border-radius:50%;background:#f0c040;display:flex;
                            align-items:center;justify-content:center;">
                    <i class="fas fa-check" style="color:#000;font-size:0.8rem;"></i>
                </div>
                <h5 style="color:#f0c040" class="mb-3">Previous Employer</h5>

                @forelse($employee->previousEmployers as $emp)
                <div class="card-dark mb-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <small style="color:#f0c040">Company Name-</small>
                            <p class="mb-0 text-white">{{ $emp->company_name ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">HR Name-</small>
                            <p class="mb-0 text-white">{{ $emp->hr_name ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">HR Phone-</small>
                            <p class="mb-0 text-white">{{ $emp->hr_phone ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">Address-</small>
                            <p class="mb-0 text-white">{{ $emp->address_line1 ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">State-</small>
                            <p class="mb-0 text-white">{{ $emp->state ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">City-</small>
                            <p class="mb-0 text-white">{{ $emp->city ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">Designation-</small>
                            <p class="mb-0 text-white">{{ $emp->designation ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">Monthly Salary-</small>
                            <p class="mb-0 text-white">{{ $emp->monthly_salary ? '₹'.$emp->monthly_salary : '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">Duration-</small>
                            <p class="mb-0 text-white">{{ $emp->duration ?? '-' }}</p>
                        </div>

                        {{-- Salary Slip --}}
<div class="col-md-3">
    <small style="color:#f0c040">Salary Slip-</small>
    @if($emp->salary_slip)
        <br>
        @php
            $ext = pathinfo($emp->salary_slip, PATHINFO_EXTENSION);
            $imgExts = ['jpg','jpeg','png','gif','webp'];
        @endphp
        @if(in_array(strtolower($ext), $imgExts))
            <a href="{{ asset('storage/'.$emp->salary_slip) }}" target="_blank">
                <img src="{{ asset('storage/'.$emp->salary_slip) }}"
                     style="width:80px;height:80px;object-fit:cover;
                            border:2px solid #f0c040;border-radius:6px;"
                     class="mt-1">
            </a>
        @else
            <a href="{{ asset('storage/'.$emp->salary_slip) }}"
               target="_blank"
               style="color:#f0c040">
               📄 View File
            </a>
        @endif
    @else
        <p class="mb-0 text-white">-</p>
    @endif
</div>
                    </div>
                </div>
                @empty
                <p class="text-muted">No previous employers added.</p>
                @endforelse
            </div>

            {{-- ===== BANK DETAILS ===== --}}
            <div style="position:relative; margin-bottom:35px;">
                <div style="position:absolute;left:-32px;top:0;width:30px;height:30px;
                            border-radius:50%;background:#f0c040;display:flex;
                            align-items:center;justify-content:center;">
                    <i class="fas fa-check" style="color:#000;font-size:0.8rem;"></i>
                </div>
                <h5 style="color:#f0c040" class="mb-3">Bank Details</h5>

                @forelse($employee->bankDetails as $bank)
                <div class="card-dark mb-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <small style="color:#f0c040">Holder Name-</small>
                            <p class="mb-0 text-white">{{ $bank->holder_name ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">Bank Name-</small>
                            <p class="mb-0 text-white">{{ $bank->bank_name ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">Account Number-</small>
                            <p class="mb-0 text-white">{{ $bank->account_number ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">IFSC Code-</small>
                            <p class="mb-0 text-white">{{ $bank->ifsc_code ?? '-' }}</p>
                        </div>

                        {{-- Photo --}}
                        <div class="col-md-3">
                            <small style="color:#f0c040">Photo-</small>
                            @if($bank->photo)
                                <br>
                                <img src="{{ asset('storage/'.$bank->photo) }}"
                                     style="width:80px;height:80px;object-fit:cover;
                                            border:2px solid #f0c040;border-radius:6px;"
                                     class="mt-1">
                            @else
                                <p class="mb-0 text-white">-</p>
                            @endif
                        </div>

                        {{-- Passbook --}}
                        <div class="col-md-3">
                            <small style="color:#f0c040">Passbook-</small>
                            @if($bank->passbook)
                                <br>
                                <a href="{{ asset('storage/'.$bank->passbook) }}"
                                   target="_blank">
                                    <img src="{{ asset('storage/'.$bank->passbook) }}"
                                         style="width:80px;height:80px;object-fit:cover;
                                                border:2px solid #f0c040;border-radius:6px;"
                                         class="mt-1">
                                </a>
                            @else
                                <p class="mb-0 text-white">-</p>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted">No bank details added.</p>
                @endforelse
            </div>

            {{-- ===== OFFICIAL DETAILS ===== --}}
            <div style="position:relative; margin-bottom:35px;">
                <div style="position:absolute;left:-32px;top:0;width:30px;height:30px;
                            border-radius:50%;background:#f0c040;display:flex;
                            align-items:center;justify-content:center;">
                    <i class="fas fa-check" style="color:#000;font-size:0.8rem;"></i>
                </div>
                <h5 style="color:#f0c040" class="mb-3">Official Details</h5>

                @if($employee->officialDetail)
                <div class="card-dark mb-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <small style="color:#f0c040">Date of Joining-</small>
                            <p class="mb-0 text-white">{{ $employee->officialDetail->doj ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">Designation-</small>
                            <p class="mb-0 text-white">{{ $employee->officialDetail->designation ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">Salary-</small>
                            <p class="mb-0 text-white">{{ $employee->officialDetail->salary ? '₹'.$employee->officialDetail->salary : '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">Branch-</small>
                            <p class="mb-0 text-white">{{ $employee->officialDetail->branch ?? '-' }}</p>
                        </div>
                        <div class="col-md-3">
                            <small style="color:#f0c040">Permission-</small>
                            <p class="mb-0 text-white">{{ $employee->officialDetail->permission ?? '-' }}</p>
                        </div>
                    </div>
                </div>
                @else
                <p class="text-muted">No official details added.</p>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
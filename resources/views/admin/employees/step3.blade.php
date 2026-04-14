@extends('layouts.admin')
@section('page-title', 'Add Employee - Step 3')

@section('content')
<h4 class="mb-3" style="color:#f0c040">Previous Employer</h4>

<div class="step-indicator mb-4">
    <div class="step done">1</div>
    <div class="step done">2</div>
    <div class="step active">3</div>
    <div class="step">4</div>
    <div class="step">5</div>
</div>

<div class="card-dark">
    <form method="POST" action="{{ route('admin.employees.saveStep3', $employee->id) }}"
          enctype="multipart/form-data">
        @csrf

        <div id="employer-items">
            @if($employee->previousEmployers->count() > 0)
                @foreach($employee->previousEmployers as $index => $emp)
                <div class="employer-item border border-secondary rounded p-3 mb-3">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="employers[{{ $index }}][company_name]"
                                   class="form-control" value="{{ $emp->company_name }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">HR Name</label>
                            <input type="text" name="employers[{{ $index }}][hr_name]"
                                   class="form-control" value="{{ $emp->hr_name }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">HR Phone Number</label>
                            <input type="text" name="employers[{{ $index }}][hr_phone]"
                                   class="form-control" value="{{ $emp->hr_phone }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Monthly Salary</label>
                            <input type="number" name="employers[{{ $index }}][monthly_salary]"
                                   class="form-control" value="{{ $emp->monthly_salary }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Designation</label>
                            <input type="text" name="employers[{{ $index }}][designation]"
                                   class="form-control" value="{{ $emp->designation }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Duration For Working</label>
                            <input type="text" name="employers[{{ $index }}][duration]"
                                   class="form-control" value="{{ $emp->duration }}">
                        </div>
                    </div>
                </div>
                @endforeach
            @else
            <div class="employer-item border border-secondary rounded p-3 mb-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="employers[0][company_name]"
                               class="form-control" placeholder="Type here...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">HR Name</label>
                        <input type="text" name="employers[0][hr_name]"
                               class="form-control" placeholder="Type here...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">HR Phone Number</label>
                        <input type="text" name="employers[0][hr_phone]"
                               class="form-control" placeholder="Type here...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Monthly Salary</label>
                        <input type="number" name="employers[0][monthly_salary]"
                               class="form-control" placeholder="Type here...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Designation</label>
                        <input type="text" name="employers[0][designation]"
                               class="form-control" placeholder="Type here...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Duration For Working</label>
                        <input type="text" name="employers[0][duration]"
                               class="form-control" placeholder="e.g. 2 Years">
                    </div>
                </div>
            </div>
            @endif
        </div>

        <button type="button" id="add-employer"
                class="btn btn-outline-warning btn-sm mb-3">
            + Add More Item
        </button>

        <div class="d-flex justify-content-between mt-2">
            <a href="{{ route('admin.employees.step2', $employee->id) }}"
               class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-gold">Next →</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let empCount = {{ $employee->previousEmployers->count() > 0 ? $employee->previousEmployers->count() : 1 }};
document.getElementById('add-employer').addEventListener('click', function() {
    const container = document.getElementById('employer-items');
    const div = document.createElement('div');
    div.className = 'employer-item border border-secondary rounded p-3 mb-3';
    div.innerHTML = `
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Company Name</label>
                <input type="text" name="employers[${empCount}][company_name]"
                       class="form-control" placeholder="Type here...">
            </div>
            <div class="col-md-4">
                <label class="form-label">HR Name</label>
                <input type="text" name="employers[${empCount}][hr_name]"
                       class="form-control" placeholder="Type here...">
            </div>
            <div class="col-md-4">
                <label class="form-label">HR Phone Number</label>
                <input type="text" name="employers[${empCount}][hr_phone]"
                       class="form-control" placeholder="Type here...">
            </div>
            <div class="col-md-4">
                <label class="form-label">Monthly Salary</label>
                <input type="number" name="employers[${empCount}][monthly_salary]"
                       class="form-control" placeholder="Type here...">
            </div>
            <div class="col-md-4">
                <label class="form-label">Designation</label>
                <input type="text" name="employers[${empCount}][designation]"
                       class="form-control" placeholder="Type here...">
            </div>
            <div class="col-md-4">
                <label class="form-label">Duration</label>
                <input type="text" name="employers[${empCount}][duration]"
                       class="form-control" placeholder="e.g. 2 Years">
            </div>
        </div>`;
    container.appendChild(div);
    empCount++;
});
</script>
@endpush
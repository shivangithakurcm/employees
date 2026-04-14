@extends('layouts.admin')
@section('page-title', 'Add Employee - Step 4')

@section('content')
<h4 class="mb-3" style="color:#f0c040">Bank Details</h4>

<div class="step-indicator mb-4">
    <div class="step done">1</div>
    <div class="step done">2</div>
    <div class="step done">3</div>
    <div class="step active">4</div>
    <div class="step">5</div>
</div>

<div class="card-dark">
    <form method="POST" action="{{ route('admin.employees.saveStep4', $employee->id) }}"
          enctype="multipart/form-data">
        @csrf

        <div id="bank-items">
            @if($employee->bankDetails->count() > 0)
                @foreach($employee->bankDetails as $index => $bank)
                <div class="bank-item border border-secondary rounded p-3 mb-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Holder Name</label>
                            <input type="text" name="bank_details[{{ $index }}][holder_name]"
                                   class="form-control" value="{{ $bank->holder_name }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bank Name</label>
                            <input type="text" name="bank_details[{{ $index }}][bank_name]"
                                   class="form-control" value="{{ $bank->bank_name }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Account Number</label>
                            <input type="text" name="bank_details[{{ $index }}][account_number]"
                                   class="form-control" value="{{ $bank->account_number }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">IFSC Code</label>
                            <input type="text" name="bank_details[{{ $index }}][ifsc_code]"
                                   class="form-control" value="{{ $bank->ifsc_code }}">
                        </div>
                    </div>
                </div>
                @endforeach
            @else
            <div class="bank-item border border-secondary rounded p-3 mb-3">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Holder Name</label>
                        <input type="text" name="bank_details[0][holder_name]"
                               class="form-control" placeholder="Type here...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bank Name</label>
                        <input type="text" name="bank_details[0][bank_name]"
                               class="form-control" placeholder="Type here...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Account Number</label>
                        <input type="text" name="bank_details[0][account_number]"
                               class="form-control" placeholder="Type here...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">IFSC Code</label>
                        <input type="text" name="bank_details[0][ifsc_code]"
                               class="form-control" placeholder="Type here...">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Upload Photo</label>
                        <input type="file" name="bank_details[0][photo]"
                               class="form-control" accept="image/*">
                        <small class="text-muted">Max size: 2MB</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Upload Passbook</label>
                        <input type="file" name="bank_details[0][passbook]"
                               class="form-control">
                        <small class="text-muted">Max size: 2MB</small>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <button type="button" id="add-bank"
                class="btn btn-outline-warning btn-sm mb-3">
            + Add More Item
        </button>

       

        <div class="d-flex justify-content-between mt-2">
            <a href="{{ route('admin.employees.step3', $employee->id) }}"
               class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-gold">Next →</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let bankCount = {{ $employee->bankDetails->count() > 0 ? $employee->bankDetails->count() : 1 }};
document.getElementById('add-bank').addEventListener('click', function() {
    const container = document.getElementById('bank-items');
    const div = document.createElement('div');
    div.className = 'bank-item border border-secondary rounded p-3 mb-3';
    div.innerHTML = `
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Holder Name</label>
                <input type="text" name="bank_details[${bankCount}][holder_name]"
                       class="form-control" placeholder="Type here...">
            </div>
            <div class="col-md-6">
                <label class="form-label">Bank Name</label>
                <input type="text" name="bank_details[${bankCount}][bank_name]"
                       class="form-control" placeholder="Type here...">
            </div>
            <div class="col-md-6">
                <label class="form-label">Account Number</label>
                <input type="text" name="bank_details[${bankCount}][account_number]"
                       class="form-control" placeholder="Type here...">
            </div>
            <div class="col-md-6">
                <label class="form-label">IFSC Code</label>
                <input type="text" name="bank_details[${bankCount}][ifsc_code]"
                       class="form-control" placeholder="Type here...">
            </div>
        </div>`;
    container.appendChild(div);
    bankCount++;
});
</script>
@endpush
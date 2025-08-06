<form id="companyProfileForm" class="row g-4 mb-4" method="POST">
    @csrf
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="companyName" name="company_name"
                   placeholder="نام شرکت" value="{{ $profile['company_name'] ?? '' }}">
            <label for="companyName">نام شرکت</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="registrationNumber" name="registration_number"
                   placeholder="شماره ثبت" value="{{ $profile['registration_number'] ?? '' }}">
            <label for="registrationNumber">شماره ثبت</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="nationalId" name="national_id"
                   placeholder="شناسه ملی" value="{{ $profile['national_id'] ?? '' }}">
            <label for="nationalId">شناسه ملی</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="companyPhone" name="company_phone"
                   placeholder="تلفن شرکت" value="{{ $profile['company_phone'] ?? '' }}">
            <label for="companyPhone">تلفن شرکت</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="email" class="form-control" id="companyEmail" name="company_email"
                   placeholder="ایمیل شرکت" value="{{ $profile['company_email'] ?? '' }}">
            <label for="companyEmail">ایمیل شرکت</label>
        </div>
    </div>
    <div class="col-12">
        <div class="form-floating form-floating-outline">
            <textarea rows="2" class="form-control" id="companyAddress" name="company_address"
                      placeholder="آدرس">{{ $profile['company_address'] ?? '' }}</textarea>
            <label for="companyAddress">آدرس شرکت</label>
        </div>
    </div>
    <div class="col-12 text-center">
        <button type="submit" class="btn btn-primary">ذخیره</button>
        <button type="button" class="btn btn-outline-secondary" onclick="toggleEditMode()">انصراف</button>
    </div>
</form>

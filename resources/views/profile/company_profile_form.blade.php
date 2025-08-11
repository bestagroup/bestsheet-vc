<form id="companyProfileForm" action="{{route('company.update' , $company->id)}}" class="row g-4 mb-4" method="POST">
    @csrf
    @method('PATCH')
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="company_name" name="company_name"
                   placeholder="نام شرکت" value="{{ $company->company_name }}">
            <label for="company_name">نام شرکت</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="commercial_name" name="commercial_name"
                   placeholder="نام تجاری شرکت" value="{{ $company->commercial_name }}">
            <label for="commercial_name">نام تجاری شرکت</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="registration_number" name="registration_number"
                   placeholder="شماره ثبت" value="{{ $company->registration_number }}">
            <label for="registration_number">شماره ثبت</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="national_id" name="national_id"
                   placeholder="شناسه ملی" value="{{ $company->national_id }}">
            <label for="national_id">شناسه ملی</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="economic_code" name="economic_code"
                   placeholder="کد اقتصادی" value="{{ $company->economic_code }}">
            <label for="economic_code">کد اقتصادی</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="legal_type" name="legal_type"
                   placeholder="نوع شرکت" value="{{ $company->legal_type }}">
            <label for="legal_type">نوع شرکت</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="phone" name="phone"
                   placeholder="تلفن شرکت" value="{{ $company->phone }}">
            <label for="phone">تلفن شرکت</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="email" class="form-control" id="email" name="email"
                   placeholder="ایمیل شرکت" value="{{ $company->email }}">
            <label for="email">ایمیل شرکت</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="website" name="website"
                   placeholder="وبسایت" value="{{ $company->website }}">
            <label for="website">وبسایت</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="province" name="province"
                   placeholder="استان" value="{{ $company->province }}">
            <label for="province">استان</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="city" name="city"
                   placeholder="شهرستان" value="{{ $company->city }}">
            <label for="city">شهرستان</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="postal_code" name="postal_code"
                   placeholder="کد پستی" value="{{ $company->postal_code }}">
            <label for="postal_code">کد پستی</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="ceo_name" name="ceo_name"
                   placeholder="مدیرعامل" value="{{ $company->ceo_name }}">
            <label for="ceo_name">مدیرعامل</label>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" id="ceo_national_code" name="ceo_national_code"
                   placeholder="کد ملی مدیرعامل" value="{{ $company->ceo_national_code }}">
            <label for="ceo_national_code">کد ملی مدیرعامل</label>
        </div>
    </div>
    <div class="col-12">
        <div class="form-floating form-floating-outline">
            <textarea rows="2" class="form-control" id="address" name="address"
                      placeholder="آدرس">{{ $company->address }}</textarea>
            <label for="companyAddress">آدرس شرکت</label>
        </div>
    </div>
    <div class="col-12 text-center">
        <button type="submit" class="btn btn-primary">ذخیره</button>
        <button type="button" class="btn btn-outline-secondary" onclick="toggleEditMode()">انصراف</button>
    </div>
</form>

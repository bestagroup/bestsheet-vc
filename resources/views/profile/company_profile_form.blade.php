            <form id="editform_{{$company->id}}" method="POST" class="row g-4 mb-4" action="{{ route('company.update', $company->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="company_name_{{$company->id}}" name="company_name"
                                   placeholder="نام شرکت" value="{{ $company->company_name }}">
                            <label for="company_name">نام شرکت</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="commercial_name_{{$company->id}}" name="commercial_name"
                                   placeholder="نام تجاری شرکت" value="{{ $company->commercial_name }}">
                            <label for="commercial_name">نام تجاری شرکت</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="registration_number_{{$company->id}}" name="registration_number"
                                   placeholder="شماره ثبت" value="{{ $company->registration_number }}">
                            <label for="registration_number">شماره ثبت</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="national_id_{{$company->id}}" name="national_id"
                                   placeholder="شناسه ملی" value="{{ $company->national_id }}">
                            <label for="national_id">شناسه ملی</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="economic_code_{{$company->id}}" name="economic_code"
                                   placeholder="کد اقتصادی" value="{{ $company->economic_code }}">
                            <label for="economic_code">کد اقتصادی</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select name="legal_type" id="legal_type_{{$company->id}}" class="form-control">
                                <option value="" selected>انتخاب کنید</option>
                                <option value="مسئولیت محدود"   {{$company->legal_type == 'مسئولیت محدود' ? 'selected' : ''}}>مسئولیت محدود</option>
                                <option value="سهامی خاص"       {{$company->legal_type == 2 ? 'سهامی خاص' : ''}}>سهامی خاص</option>
                                <option value="سهامی عام"       {{$company->legal_type == 2 ? 'سهامی عام' : ''}}>سهامی عام</option>
                                <option value="تعاونی"          {{$company->legal_type == 2 ? 'تعاونی' : ''}}>تعاونی</option>
                                <option value="موسسه غیر تجاری" {{$company->legal_type == 2 ? 'موسسه غیر تجاری' : ''}}>موسسه غیر تجاری</option>
                            </select>
                            <label for="legal_type">نوع شرکت</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="phone_{{$company->id}}" name="phone"
                                   placeholder="تلفن شرکت" value="{{ $company->phone }}">
                            <label for="phone">تلفن شرکت</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="email" class="form-control" id="email_{{$company->id}}" name="email"
                                   placeholder="ایمیل شرکت" value="{{ $company->email }}">
                            <label for="email">ایمیل شرکت</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="website_{{$company->id}}" name="website"
                                   placeholder="وبسایت" value="{{ $company->website }}">
                            <label for="website">وبسایت</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="province_{{$company->id}}" name="province"
                                   placeholder="استان" value="{{ $company->province }}">
                            <label for="province">استان</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="city_{{$company->id}}" name="city"
                                   placeholder="شهرستان" value="{{ $company->city }}">
                            <label for="city">شهرستان</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="postal_code_{{$company->id}}" name="postal_code"
                                   placeholder="کد پستی" value="{{ $company->postal_code }}">
                            <label for="postal_code">کد پستی</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="ceo_name_{{$company->id}}" name="ceo_name"
                                   placeholder="مدیرعامل" value="{{ $company->ceo_name }}">
                            <label for="ceo_name">مدیرعامل</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" id="ceo_national_code_{{$company->id}}" name="ceo_national_code"
                                   placeholder="کد ملی مدیرعامل" value="{{ $company->ceo_national_code }}">
                            <label for="ceo_national_code">کد ملی مدیرعامل</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating form-floating-outline">
                            <textarea rows="2" class="form-control" id="address_{{$company->id}}" name="address"
                                      placeholder="آدرس">{{ $company->address }}</textarea>
                            <label for="companyAddress">آدرس شرکت</label>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-primary" id="editsubmit_{{$company->id}}">ذخیره</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="toggleEditMode('company')">
                            انصراف
                        </button>
                    </div>
                </form>

            <form id="editform_{{$company->id}}" method="POST" class="row g-4 mb-4" action="{{ route('company.update', $company->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input required type="text" class="form-control" id="company_name_{{$company->id}}" name="company_name"
                                   placeholder="نام شرکت" value="{{ $company->company_name }}">
                            <label for="company_name">نام شرکت</label>
                            <div class="invalid-feedback" id="company_nameFeedback">نام شرکت اجباری می باشد.</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input required type="text" class="form-control" id="commercial_name_{{$company->id}}" name="commercial_name"
                                   placeholder="برند شرکت" value="{{ $company->commercial_name }}">
                            <label for="commercial_name">نام طرح</label>
                            <div class="invalid-feedback" id="commercial_nameFeedback">نام طرح اجباری می باشد.</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input required inputmode="numeric" pattern="^\d{10}$" maxlength="16" minlength="10" type="text" class="form-control" id="registration_number_{{$company->id}}" name="registration_number"
                                   placeholder="شماره ثبت" value="{{ $company->registration_number }}">
                            <label for="registration_number">شماره ثبت</label>
                            <div class="invalid-feedback" id="registration_numberFeedback">شماره ثبت اجباری و شامل عدد می باشد.</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input required inputmode="numeric" pattern="^\d{10}$" maxlength="16" minlength="10" type="text" class="form-control" id="national_id_{{$company->id}}" name="national_id"
                                   placeholder="شناسه ملی شرکت" value="{{ $company->national_id }}">
                            <label for="national_id">شناسه ملی شرکت</label>
                            <div class="invalid-feedback" id="national_idFeedback">شناسه ملی شرکت اجباری و شامل عدد می باشد.</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input required inputmode="numeric" pattern="^\d{10}$" maxlength="16" minlength="10" type="text" class="form-control" id="economic_code_{{$company->id}}" name="economic_code"
                                   placeholder="کد اقتصادی شرکت" value="{{ $company->economic_code }}">
                            <label for="economic_code">کد اقتصادی شرکت</label>
                            <div class="invalid-feedback" id="economic_codeFeedback">کد اقتصادی اجباری، و شامل عدد می باشد.</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select name="legal_type" id="legal_type_{{$company->id}}" class="form-control">
                                <option value="" selected>انتخاب کنید</option>
                                <option value="مسئولیت محدود"   {{$company->legal_type == 'مسئولیت محدود' ? 'selected' : ''}}>مسئولیت محدود</option>
                                <option value="سهامی خاص"       {{$company->legal_type == 'سهامی خاص' ? 'selected' : ''}}>سهامی خاص</option>
                                <option value="سهامی عام"       {{$company->legal_type == 'سهامی عام' ? 'selected' : ''}}>سهامی عام</option>
                                <option value="تعاونی"          {{$company->legal_type == 'تعاونی' ? 'selected' : ''}}>تعاونی</option>
                                <option value="موسسه غیر تجاری" {{$company->legal_type == 'موسسه غیر تجاری' ? 'selected' : ''}}>موسسه غیر تجاری</option>
                            </select>
                            <label for="legal_type">نوع شرکت</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input inputmode="numeric" pattern="^\d{10}$" type="text" class="form-control" id="phone_{{$company->id}}" name="phone"
                                   placeholder="تلفن شرکت" value="{{ $company->phone }}">
                            <label for="phone">تلفن شرکت</label>
                            <div class="invalid-feedback" id="phoneFeedback">شماره تلفن شامل عدد می باشد.</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="email" class="form-control" id="email_{{$company->id}}" name="email"
                                   placeholder="ایمیل شرکت" value="{{ $company->email }}">
                            <label for="email">ایمیل شرکت</label>
                            <div class="invalid-feedback" id="emailFeedback">آدرس ایمیل را با دقت وارد کنید.</div>
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
                            <input required type="text" class="form-control" id="postal_code_{{$company->id}}" name="postal_code"
                                   placeholder="کد پستی" value="{{ $company->postal_code }}">
                            <label for="postal_code">کد پستی</label>
                            <div class="invalid-feedback" id="postal_codeFeedback">کد پستی باید به شکل عدد 10 رقمی وارد شود</div>
                        </div>
                    </div>
                <div class="col-12 col-md-6">
                    <div class="form-floating form-floating-outline">
                        <select name="state" id="state_{{$company->id}}" class="form-control select2">
                            <option value="" selected>انتخاب کنید</option>
                            @foreach($states as $state)
                                <option value="{{$state->id}}" {{$company->province == $state->id ? 'selected' : ''}}>
                                    {{$state->title}}
                                </option>
                            @endforeach
                        </select>
                        <label for="state">استان</label>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-floating form-floating-outline">
                        <select name="city" id="city_{{$company->id}}" class="form-control select2">
                            <option value="" selected>انتخاب کنید</option>
                            @foreach($cities as $city)
                                <option value="{{$city->id}}" {{$company->city == $city->id ? 'selected' : ''}}>
                                    {{$city->title}}
                                </option>
                            @endforeach
                        </select>
                        <label for="city">شهرستان</label>
                    </div>
                </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input required type="text" class="form-control" id="ceo_name_{{$company->id}}" name="ceo_name"
                                   placeholder="مدیرعامل" value="{{ $company->ceo_name }}">
                            <label for="ceo_name">مدیرعامل</label>
                            <div class="invalid-feedback" id="ceo_nameFeedback">نام مدیرعامل اجباری می باشد.</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input required inputmode="numeric" pattern="^\d{10}$" maxlength="10" minlength="10" type="text" class="form-control" id="ceo_national_code_{{$company->id}}" name="ceo_national_code"
                                   placeholder="کد ملی مدیرعامل" value="{{ $company->ceo_national_code }}">
                            <label for="ceo_national_code">کد ملی مدیرعامل</label>
                            <div class="invalid-feedback" id="ceo_national_codeFeedback">کد ملی مدیرعامل اجباری می باشد و با دقت وارد شود</div>
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

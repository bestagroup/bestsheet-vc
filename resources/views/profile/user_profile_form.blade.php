
                    <form id="editform_{{Auth::user()->id}}" method="POST" class="row g-4 mb-4" action="{{ route('company.update', Auth::user()->id) }}">
                        @csrf
                        @method('PATCH')
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="name" name="name" class="form-control"
                                   placeholder="نام و نام خانوادگی" value="{{ Auth::user()->name }}">
                            <label for="name">نام و نام خانوادگی</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="username" name="username" class="form-control"
                                   placeholder="نام کاربری" value="{{ Auth::user()->username }}">
                            <label for="username">نام کاربری</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="national_id" name="national_id" class="form-control"
                                   placeholder="کد ملی" value="{{ Auth::user()->national_id }}">
                            <label for="national_id">کد ملی</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="email" id="email" name="email" class="form-control"
                                   placeholder="ایمیل" value="{{ Auth::user()->email }}">
                            <label for="email">ایمیل</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="number" id="age" name="age" class="form-control"
                                   placeholder="سن" value="{{ Auth::user()->age ?? '' }}">
                            <label for="age">سن</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select id="gender" name="gender" class="form-select">
                                <option value="">انتخاب</option>
                                <option value="1" {{ Auth::user()->gender == 1 ? 'selected' : '' }}>مرد</option>
                                <option value="2" {{ Auth::user()->gender == 2 ? 'selected' : '' }}>زن</option>
                            </select>
                            <label for="gender">جنسیت</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="phone" name="phone" class="form-control"
                                   placeholder="شماره موبایل" value="{{ Auth::user()->phone }}">
                            <label for="phone">شماره موبایل</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="telephone" name="telephone" class="form-control"
                                   placeholder="شماره تماس" value="{{ Auth::user()->telephone ?? '' }}">
                            <label for="telephone">شماره تماس</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating form-floating-outline">
                        <textarea id="address" name="address" class="form-control" rows="3"
                                  placeholder="آدرس">{{ Auth::user()->address ?? '' }}</textarea>
                            <label for="address">آدرس ثبتی</label>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-primary" id="editsubmit_{{Auth::user()->id}}">ذخیره</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="toggleEdituserMode()">انصراف</button>
                    </div>
                </form>

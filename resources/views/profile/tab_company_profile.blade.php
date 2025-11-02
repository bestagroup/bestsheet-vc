<div class="tab-pane fade justify-content-center" id="navs-co-profile-card" role="tabpanel">
    <div class="modal fade" id="companyeditModal" tabindex="-1" aria-labelledby="companyeditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="companyModalLabel">اطلاعات مدیرعامل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="card-body">
                    <div class="modal-body">
                        <form data-type="update" data-id="{{ $project->id }}"  class="row g-4 mb-4" method="POST" action="{{ route('company.update', $project->id) }}">
                            @csrf
                            @method('PATCH')
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input required type="text" class="form-control" id="company_name_{{$project->id}}" name="company_name"
                                           placeholder="نام شرکت" value="{{ $project->company_name }}">
                                    <label for="company_name">نام شرکت</label>
                                    <div class="invalid-feedback" id="company_nameFeedback">نام شرکت اجباری می باشد.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input required type="text" class="form-control" id="title_{{$project->id}}" name="title"
                                           placeholder="نام طرح" value="{{ $project->title }}">
                                    <label for="title">نام طرح</label>
                                    <div class="invalid-feedback" id="titleFeedback">نام طرح اجباری می باشد.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input required inputmode="numeric" pattern="^\d+$" type="text" class="form-control" id="registration_number_{{$project->id}}" name="registration_number"
                                           placeholder="شماره ثبت" value="{{ $project->registration_number }}">
                                    <label for="registration_number">شماره ثبت</label>
                                    <div class="invalid-feedback" id="registration_numberFeedback">شماره ثبت اجباری و شامل عدد می باشد.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input required inputmode="numeric" pattern="^\d+$" type="text" class="form-control" id="national_id_{{$project->id}}" name="national_id"
                                           placeholder="شناسه ملی شرکت" value="{{ $project->national_id }}">
                                    <label for="national_id">شناسه ملی شرکت</label>
                                    <div class="invalid-feedback" id="national_idFeedback">شناسه ملی شرکت اجباری و شامل عدد می باشد.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input required inputmode="numeric" pattern="^\d+$" type="text" class="form-control" id="economic_code_{{$project->id}}" name="economic_code"
                                           placeholder="کد اقتصادی شرکت" value="{{ $project->economic_code }}">
                                    <label for="economic_code">کد اقتصادی شرکت</label>
                                    <div class="invalid-feedback" id="economic_codeFeedback">کد اقتصادی اجباری، و شامل عدد می باشد.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <select name="legal_type" id="legal_type_{{$project->id}}" class="form-control">
                                        <option value="" selected>انتخاب کنید</option>
                                        <option value="مسئولیت محدود"   {{$project->legal_type == 'مسئولیت محدود' ? 'selected' : ''}}>مسئولیت محدود</option>
                                        <option value="سهامی خاص"       {{$project->legal_type == 'سهامی خاص' ? 'selected' : ''}}>سهامی خاص</option>
                                        <option value="سهامی عام"       {{$project->legal_type == 'سهامی عام' ? 'selected' : ''}}>سهامی عام</option>
                                        <option value="تعاونی"          {{$project->legal_type == 'تعاونی' ? 'selected' : ''}}>تعاونی</option>
                                        <option value="موسسه غیر تجاری" {{$project->legal_type == 'موسسه غیر تجاری' ? 'selected' : ''}}>موسسه غیر تجاری</option>
                                    </select>
                                    <label for="legal_type">نوع شرکت</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input inputmode="numeric" pattern="^\d+$" type="text" class="form-control" id="tel_{{$project->id}}" name="tel" placeholder="تلفن شرکت" value="{{ $project->tel }}">
                                    <label for="tel">تلفن شرکت</label>
                                    <div class="invalid-feedback" id="telFeedback">شماره تلفن شامل عدد می باشد.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="email" class="form-control" id="email_{{$project->id}}" name="email" placeholder="ایمیل شرکت" value="{{ $project->email }}">
                                    <label for="email">ایمیل شرکت</label>
                                    <div class="invalid-feedback" id="emailFeedback">آدرس ایمیل را با دقت وارد کنید.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="website_{{$project->id}}" name="website" placeholder="وبسایت" value="{{ $project->website }}">
                                    <label for="website">وبسایت</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input required type="text" class="form-control" id="postal_code_{{$project->id}}" name="postal_code" placeholder="کد پستی" value="{{ $project->postal_code }}">
                                    <label for="postal_code">کد پستی</label>
                                    <div class="invalid-feedback" id="postal_codeFeedback">کد پستی باید به شکل عدد 10 رقمی وارد شود</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <select name="state" id="state_{{$project->id}}" class="form-control select2">
                                        <option value="" selected>انتخاب کنید</option>
                                        @foreach($states as $state)
                                            <option value="{{$state->id}}" {{$project->state == $state->id ? 'selected' : ''}}>
                                                {{$state->title}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="state">استان</label>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <select name="city" id="city_{{$project->id}}" class="form-control select2">
                                        <option value="" selected>انتخاب کنید</option>
                                        @foreach($cities as $city)
                                            <option value="{{$city->id}}" {{$project->city == $city->id ? 'selected' : ''}}>
                                                {{$city->title}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="city">شهرستان</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating form-floating-outline">
                            <textarea rows="2" class="form-control" id="address_{{$project->id}}" name="address"
                                      placeholder="آدرس">{{ $project->address }}</textarea>
                                    <label for="address">آدرس شرکت</label>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary" id="editsubmit_{{$project->id}}">ذخیره</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <table id="companyTable" class="table yajra-datatable yajra-datatable-company">
        <thead class="d-none">
        <tr>
            <th>company</th>
        </tr>
        </thead>
    </table>

    @push('scripts')
        <script>
            // جدول شرکت
            $(function() {
                $('#companyTable.yajra-datatable-company').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('companydata') }}',
                    columns: [
                        {
                            data: null,
                            render: function(data) {
                                return `
                        <div class="card border-0 shadow-sm mb-4" style="max-width:480px; margin:0 auto; border-radius:1.25rem;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex justify-content-center align-items-center shadow-sm" style="width:56px; height:56px; background:#f2f3f6;">
                                            <i class="mdi mdi-domain" style="font-size:2rem; color:#696cff"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold mb-1" style="font-size:1.2rem;">${data.title ?? ''}</div>
                                            <div class="small text-secondary" dir="ltr" style="font-size:0.95rem;">${data.company_name ?? ''}</div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" data-id="${data.id ?? ''}" data-bs-toggle="modal" data-bs-target="#companyeditModal">
                                        <span class="d-none d-md-inline">ویرایش</span>
                                    </button>
                                </div>
                                <dl class="row g-3" style="font-size:0.95rem;">
                                    <div class="col-12 d-flex">
                                        <dt class="col-5 text-start text-muted">شماره ثبت :</dt>
                                        <dd class="col-7 text-dark mb-0">${data.registration_number ?? ''}</dd>
                                    </div>
                                    <div class="col-12 d-flex border-top pt-3">
                                        <dt class="col-5 text-start text-muted">کد اقتصادی :</dt>
                                        <dd class="col-7 text-dark mb-0">${data.economic_code ?? ''}</dd>
                                    </div>
                                    <div class="col-12 d-flex border-top pt-3">
                                        <dt class="col-5 text-start text-muted">تاریخ ثبت شرکت :</dt>
                                        <dd class="col-7 text-dark mb-0">${data.registration_date ?? ''}</dd>
                                    </div>
                                    <div class="col-12 d-flex border-top pt-3">
                                        <dt class="col-5 text-start text-muted">شماره شرکت:</dt>
                                        <dd class="col-7 text-dark mb-0">${data.tel ?? ''}</dd>
                                    </div>
                                    <div class="col-12 d-flex border-top pt-3">
                                        <dt class="col-5 text-start text-muted">ایمیل:</dt>
                                        <dd class="col-7 text-dark mb-0">${data.email ?? ''}</dd>
                                    </div>
                                    <div class="col-12 d-flex border-top pt-3">
                                        <dt class="col-5 text-start text-muted">وب‌سایت:</dt>
                                        <dd class="col-7 text-dark mb-0">${data.website ?? ''}</dd>
                                    </div>
                                    <div class="col-12 d-flex border-top pt-3">
                                        <dt class="col-5 text-start text-muted">آدرس:</dt>
                                        <dd class="col-7 text-dark mb-0" style="max-width:200px; word-wrap:break-word; white-space:normal;">${data.address ?? ''}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>`;
                            }
                        }
                    ],
                    paging: false,
                    searching: false,
                    ordering: false,
                    info: false,
                    language: { emptyTable: "شرکتی یافت نشد" }
                });
            });

        </script>
    @endpush

</div>


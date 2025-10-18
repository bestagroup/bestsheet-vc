<form data-type="update" data-id="{{ $mediafile->id }}"  class="row g-4 mb-4" method="POST" action="{{ route('filemanager.update', $mediafile->id) }}">
        @csrf
        @method('PATCH')
    <div class="col-12 col-md-4">
        <div class="form-floating form-floating-outline">
            <select name="company_id" id="company_id_{{$mediafile->id}}" class="form-control">
                @foreach($companies as $company)
                    <option value="{{$company->id}}" {{$company->id == $mediafile->project_id ? 'selected' : '' }}>{{$company->title}}</option>
                @endforeach
            </select>
            <label for="company_id">انتخاب شرکت</label>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="form-floating form-floating-outline">
            <select name="subject_id" id="subject_id_{{$mediafile->id}}" class="form-control">
                @foreach($subject_files as $subject_file)
                    <option value="{{$subject_file->id}}" {{$mediafile->subject_id == $subject_file->id ? 'selected' : '' }}>{{$subject_file->title}}</option>
                @endforeach
            </select>
            <label for="subject_id">انتخاب نوع فایل</label>
        </div>
    </div>
    <div class="text-end">
        <button type="submit" id="editsubmit_{{$mediafile->id}}" class="btn btn-primary" >ذخیره اطلاعات</button>
    </div>
</form>

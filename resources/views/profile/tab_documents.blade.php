<div class="tab-pane fade justify-content-center" id="navs-file-and-doc-card" role="tabpanel">
    <div class="card shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold mb-3">لیست فایل‌ها و مستندات</h6>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th style="width:40px"></th>
                        <th>نام فایل</th>
                        <th>موضوع فایل</th>
                        <th>حجم</th>
                        <th>تاریخ بارگذاری</th>
                        <th class="text-center" style="width:120px">عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($files as $file)
                            @php
                                $extension = strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION));
                                $mime = $file->mime_type ?? '';
                                $sizeInKB = number_format($file->size / 1024, 1);
                            @endphp
                            @if(count($files) > 0)

                                <tr>
                                    <td>
                                        @if ($file->mime == 'image/jpeg')
                                            <img src="{{ asset('storage/' . $file->file_path) }}" width="100" alt="{{ $file->file_name }}" class="file-preview">
                                        @elseif (Str::startsWith($mime, 'video/'))
                                            <i class="mdi mdi-video-box mdi-48px file-icon text-blue-500"></i>
                                        @elseif (Str::startsWith($mime, 'audio/'))
                                            <i class="mdi mdi-music mdi-48px file-icon text-pink-500"></i>
                                        @elseif ($file->mime == 'application/pdf')
                                            <i class="mdi mdi-file-pdf-box mdi-48px file-icon text-red-500"></i>
                                        @elseif (Str::contains($mime, 'msword') || Str::contains($mime, 'wordprocessingml'))
                                            <i class="mdi mdi-file-word mdi-48px file-icon text-blue-700"></i>
                                        @elseif (Str::contains($mime, 'ms-excel') || Str::contains($mime, 'spreadsheetml'))
                                            <i class="mdi mdi-file-excel mdi-48px file-icon text-green-600"></i>
                                        @elseif (Str::contains($mime, 'powerpoint') || Str::contains($mime, 'presentationml'))
                                            <i class="mdi mdi-file-powerpoint mdi-48px file-icon text-orange-500"></i>
                                        @elseif (in_array($extension, ['zip', 'rar', '7z']))
                                            <i class="mdi mdi-folder-zip mdi-48px file-icon text-yellow-600"></i>
                                        @else
                                            <i class="mdi mdi-file mdi-48px file-icon text-gray-500"></i>
                                        @endif
                                    </td>
                                    <td>{{ $file->original_name }}</td>
                                    <td>{{ DB::table('subject_files')->whereId($file->subject_id)->pluck('title')->first() }}</td>
                                    <td>{{ $sizeInKB }} KB</td>
                                    <td>{{ jdate($file->created_at)->format('%Y/%m/%d') }}</td>
                                    <td class="text-center">
                                        <a href="{{asset('storage/' . $file->file_path)}}" class="btn btn-sm btn-outline-primary" title="دانلود"><i class="mdi mdi-download"></i> دریافت</a>
                                    </td>
                                </tr>
                            @else
                                <tr><td colspan="7" class="text-center text-muted py-4">هیچ فایلی ثبت نشده است.</td></tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hệ thống ôn thi trắc nghiệm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />

    @include('partials.style')

  </head>
  <body>

    <div class="container">
        <h1 class="text-2xl font-bold mb-6">Quản lý câu hỏi</h1>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
            Thêm câu hỏi mới</button>

        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">STT</th>
                    <th class="border p-2">Nội dung</th>
                    <th class="border p-2">Phần</th>
                    <th class="border p-2">Độ khó</th>
                    <th class="border p-2">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($questions as $question)
                <tr>
                    <td class="border p-2">{{ $loop->iteration }}</td>
                    <td class="border p-2">{{ Str::limit($question->content, 100) }}</td>
                    <td class="border p-2">{{ $question->section }}</td>
                    <td class="border p-2">{{ $question->level }}</td>
                    <td class="border p-2">
                        <a href="{{ route('questions.edit', $question) }}" class="text-blue-500">Sửa</a>
                        <form action="{{ route('questions.destroy', $question) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 ml-4" onclick="return confirm('Xóa thật không?')">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $questions->links() }}
    </div>

    @include('partials.footer')

    <div class="modal fade" id="submitModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Xác nhận nộp bài</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>Bạn chắc chắn muốn kết thúc bài thi?</p>
            <ul>
              <li>Số câu đã làm: <strong id="modal-done">0</strong></li>
              <li>Số câu chưa làm: <strong id="modal-remain">0</strong></li>
            </ul>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Làm tiếp</button>
            <button type="button" class="btn btn-primary" onclick="alert('Đã nộp bài thành công!')">Đồng ý nộp</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

<div class="modal fade" id="modalCreate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Thêm câu hỏi</h1>
           <div class="container">
            <h1>Import câu hỏi cho đề thi: LPT</h1>

            <form method="POST" action="{{ route('admin.questions.import', $exam->id) }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="word_file">Upload file Word (.docx)</label>
                    <input type="file" name="word_file" id="word_file" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Import</button>
            </form>
        </div>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-primary">Lưu</button>
      </div>
    </form>
    </div>
  </div>
</div>

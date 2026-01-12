<div class="modal fade" id="modalCreate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Thêm câu hỏi</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="mb-4">
              <label class="block">Nội dung câu hỏi</label>
              <textarea name="content" rows="4" class="w-full border p-2"></textarea>
          </div>

          <div class="mb-4">
              <label class="block">Phần</label>
              <select name="section" class="w-full border p-2">
                  <option value="I">Phần I</option>
                  <option value="II">Phần II</option>
                  <option value="III">Phần III</option>
              </select>
          </div>

          <div class="mb-4">
              <label class="block">Độ khó (1-5)</label>
              <input type="number" name="level" min="1" max="5" value="" class="w-full border p-2">
          </div>

          <div class="mb-4">
              <label class="block">Các đáp án (4 đáp án)</label>
              @for($i = 0; $i < 4; $i++)
              <div class="flex mb-2">
                  <input type="text" name="options[{{ $i }}][content]" value="" class="flex-1 border p-2 mr-2">
                  <label class="flex items-center">
                      <input type="radio" name="correct_option" value="">
                      Đúng
                  </label>
              </div>
              @endfor
          </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-primary">Lưu</button>
      </div>
    </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalCreate" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Thêm câu hỏi</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('customer.add.post') }}" method="POST" enctype="multipart/form-data">
          @csrf
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Tên</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name">
        </div>
        <div  class="mb-3">
            <label for="floatingInputValue">Gmail</label>
            <input type="email" class="form-control" id="usermail" name="email" placeholder="@gmail.com">
        </div>
        <div class="mb-3">
            <label for="numberphone" class="form-label">Điện thoại</label>
            <input type="text" class="form-control" id="tel_num" name="tel_num">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Địa chỉ</label>
            <textarea name="address" id="address" cols="20" rows="3" class="form-control"></textarea>
        </div>
        <div class="mb-3 form-check">
            <label class="form-check-label" for="exampleCheck1">Trạng thái</label>
            <fieldset class="form-group row">
              <div class="col-sm-10">
                <div class="form-check">
                  <label class="form-check-label col-sm-3">
                    <input class="form-check-input radio-inline" type="radio" name="is_active" id="is_active" value="1" checked>Yes</label>
                    <label class="form-check-label">
                    <input class="form-check-input radio-inline" type="radio" name="is_active" id="is_active" value="2">No</label>
                </div>
                </div>
            </fieldset>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-primary">Lưu</button>
      </div>
    </form>
    </div>
  </div>
</div>

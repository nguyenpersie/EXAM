@extends('layouts.master')

@section('content')
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card shadow">
          <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
              <h4 class="mb-0"><i class="bi bi-pencil-square"></i> {{ $exam->title }}</h4>
              <a href="{{ route('exams.show', $exam->id) }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
              </a>
            </div>
          </div>
          <div class="card-body">
            <div class="mb-4">
              <p class="text-muted">Mã đề: {{ $exam->code }} | Tổng số câu: {{ $exam->questions->count() }}</p>
            </div>

            <form id="examForm">
              @foreach($exam->questions as $qIndex => $question)
                <div class="card mb-4 question-card">
                  <div class="card-header bg-light">
                    <strong>Câu {{ $qIndex + 1 }}:</strong> {!! $question->content !!}
                    @if($question->image)
                      <div class="mt-2">
                        <img src="{{ asset('storage/' . $question->image) }}" alt="Hình câu hỏi" class="img-thumbnail"
                          style="max-height: 200px;">
                      </div>
                    @endif
                  </div>
                  <div class="card-body">
                    @foreach($question->options as $oIndex => $option)
                      <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="question_{{ $question->id }}"
                          id="option_{{ $option->id }}" value="{{ $option->id }}" data-question="{{ $question->id }}">
                        <label class="form-check-label" for="option_{{ $option->id }}">
                          <strong>{{ chr(65 + $oIndex) }}.</strong> {{ $option->content }}
                          @if($option->image)
                            <img src="{{ asset('storage/' . $option->image) }}" alt="Hình đáp án" class="img-thumbnail ms-2"
                              style="max-height: 100px;">
                          @endif
                        </label>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endforeach

              <div class="d-flex gap-2 justify-content-center mt-4">
                <button type="button" class="btn btn-success btn-lg" id="submitExam">
                  <i class="bi bi-check-circle"></i> Nộp bài
                </button>
                <a href="{{ route('exams.show', $exam->id) }}" class="btn btn-secondary btn-lg">
                  <i class="bi bi-x-circle"></i> Hủy
                </a>
              </div>
            </form>

            <!-- Kết quả -->
            <div id="resultSection" class="mt-4" style="display: none;">
              <div class="alert alert-info">
                <h4><i class="bi bi-bar-chart"></i> Kết quả</h4>
                <p class="mb-0">Số câu đúng: <strong id="correctCount">0</strong> / {{ $exam->questions->count() }}</p>
                <p class="mb-0">Điểm: <strong id="scorePercent">0</strong>%</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('submitExam').addEventListener('click', function () {
      const questions = @json($exam->questions->map(function ($q) {
        return [
          'id' => $q->id,
          'correct_option_id' => $q->options->firstWhere('is_correct', true)?->id
        ];
      }));

      let correctCount = 0;

      questions.forEach(q => {
        const selected = document.querySelector(`input[name="question_${q.id}"]:checked`);
        const card = document.querySelector(`input[name="question_${q.id}"]`).closest('.question-card');

        if (selected && parseInt(selected.value) === q.correct_option_id) {
          correctCount++;
          card.classList.add('border-success');
          card.classList.remove('border-danger');
        } else {
          card.classList.add('border-danger');
          card.classList.remove('border-success');
        }

        // Highlight correct answer
        const correctRadio = document.querySelector(`input[value="${q.correct_option_id}"]`);
        if (correctRadio) {
          correctRadio.closest('.form-check').classList.add('text-success', 'fw-bold');
        }
      });

      const total = questions.length;
      const percent = Math.round((correctCount / total) * 100);

      document.getElementById('correctCount').textContent = correctCount;
      document.getElementById('scorePercent').textContent = percent;
      document.getElementById('resultSection').style.display = 'block';

      // Disable all inputs
      document.querySelectorAll('input[type="radio"]').forEach(input => input.disabled = true);
      this.disabled = true;

      // Scroll to result
      document.getElementById('resultSection').scrollIntoView({ behavior: 'smooth' });
    });
  </script>
@endsection
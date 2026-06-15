@extends('layouts.master')

@section('styles')
<style>
  :root {
    --intro-blue: #0056b3;
    --intro-blue-dark: #003d80;
    --intro-orange: #f39c12;
    --intro-bg: #f4f6f9;
  }

  .intro-page {
    background: var(--intro-bg);
  }

  /* Navbar */
  .intro-navbar {
    background: #fff;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    position: sticky;
    top: 0;
    z-index: 1000;
  }

  .intro-navbar .navbar-brand img {
    width: 48px;
    height: 48px;
    object-fit: contain;
  }

  .intro-navbar .nav-link {
    color: #444;
    font-weight: 500;
    padding: 0.5rem 1rem !important;
    transition: color 0.2s;
  }

  .intro-navbar .nav-link:hover {
    color: var(--intro-blue);
  }

  .btn-intro-login {
    background: var(--intro-blue);
    color: #fff;
    border-radius: 2rem;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
    border: none;
    transition: all 0.3s;
  }

  .btn-intro-login:hover {
    background: var(--intro-blue-dark);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 86, 179, 0.3);
  }

  /* Hero */
  .intro-hero {
    background: linear-gradient(135deg, var(--intro-blue) 0%, var(--intro-blue-dark) 60%, #002855 100%);
    color: #fff;
    padding: 4rem 0 5rem;
    position: relative;
    overflow: hidden;
  }

  .intro-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(243, 156, 18, 0.15) 0%, transparent 70%);
    border-radius: 50%;
  }

  .intro-hero::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 60px;
    background: var(--intro-bg);
    clip-path: ellipse(55% 100% at 50% 100%);
  }

  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 2rem;
    padding: 0.4rem 1rem;
    font-size: 0.875rem;
    margin-bottom: 1.5rem;
  }

  .hero-title {
    font-size: clamp(1.75rem, 4vw, 2.75rem);
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 1rem;
  }

  .hero-title span {
    color: var(--intro-orange);
  }

  .hero-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    max-width: 540px;
    line-height: 1.7;
    margin-bottom: 2rem;
  }

  .hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
  }

  .btn-hero-primary {
    background: var(--intro-orange);
    color: #fff;
    border: none;
    border-radius: 2rem;
    padding: 0.75rem 2rem;
    font-weight: 700;
    font-size: 1rem;
    transition: all 0.3s;
  }

  .btn-hero-primary:hover {
    background: #e67e22;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(243, 156, 18, 0.4);
  }

  .btn-hero-outline {
    background: transparent;
    color: #fff;
    border: 2px solid rgba(255, 255, 255, 0.6);
    border-radius: 2rem;
    padding: 0.75rem 2rem;
    font-weight: 600;
    transition: all 0.3s;
  }

  .btn-hero-outline:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    border-color: #fff;
  }

  .hero-visual {
    position: relative;
    z-index: 1;
  }

  .hero-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 1.25rem;
    padding: 1.5rem;
  }

  .hero-stat-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }

  .hero-stat-item {
    text-align: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 0.75rem;
  }

  .hero-stat-item .value {
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--intro-orange);
  }

  .hero-stat-item .label {
    font-size: 0.8rem;
    opacity: 0.85;
  }

  /* Section common */
  .intro-section {
    padding: 4rem 0;
  }

  .section-header {
    text-align: center;
    margin-bottom: 3rem;
  }

  .section-tag {
    display: inline-block;
    background: rgba(0, 86, 179, 0.1);
    color: var(--intro-blue);
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    padding: 0.35rem 1rem;
    border-radius: 2rem;
    margin-bottom: 0.75rem;
  }

  .section-title {
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 800;
    color: #1a1a2e;
    margin-bottom: 0.75rem;
  }

  .section-desc {
    color: #666;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.7;
  }

  /* About */
  .about-card {
    background: #fff;
    border-radius: 1.25rem;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    height: 100%;
  }

  .about-info-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .about-info-list li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
  }

  .about-info-list li:last-child {
    border-bottom: none;
  }

  .about-info-list i {
    color: var(--intro-blue);
    font-size: 1.1rem;
    margin-top: 0.15rem;
    flex-shrink: 0;
  }

  .about-info-list strong {
    display: block;
    color: #333;
    font-size: 0.85rem;
  }

  .about-info-list span {
    color: #666;
    font-size: 0.9rem;
  }

  /* Course cards */
  .course-card {
    background: #fff;
    border-radius: 1.25rem;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.35s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
  }

  .course-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(0, 86, 179, 0.15);
  }

  .course-card-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
  }

  .course-card-body {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .course-code {
    display: inline-block;
    background: rgba(0, 86, 179, 0.1);
    color: var(--intro-blue);
    font-size: 0.75rem;
    font-weight: 700;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    margin-bottom: 0.75rem;
  }

  .course-card h5 {
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 0.5rem;
  }

  .course-card p {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.6;
    flex: 1;
  }

  .course-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #888;
    font-size: 0.85rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #f0f0f0;
  }

  .course-ranks {
    display: flex;
    flex-wrap: wrap;
    gap: 0.35rem;
    margin-top: 0.75rem;
  }

  .course-rank-badge {
    background: #f0f4f8;
    color: var(--intro-blue);
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.2rem 0.6rem;
    border-radius: 0.35rem;
  }

  /* Rank cards */
  .rank-section {
    background: #fff;
  }

  .rank-group-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--intro-blue);
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--intro-orange);
    display: inline-block;
  }

  .rank-card {
    background: var(--intro-bg);
    border-radius: 1rem;
    padding: 1.25rem;
    text-align: center;
    transition: all 0.3s;
    height: 100%;
    border: 2px solid transparent;
  }

  .rank-card:hover {
    border-color: var(--intro-blue);
    background: #fff;
    box-shadow: 0 4px 16px rgba(0, 86, 179, 0.1);
  }

  .rank-icon {
    width: 52px;
    height: 52px;
    background: linear-gradient(135deg, var(--intro-blue), var(--intro-blue-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.75rem;
  }

  .rank-icon i {
    color: #fff;
    font-size: 1.25rem;
  }

  .rank-code {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--intro-blue);
    margin-bottom: 0.25rem;
  }

  .rank-name {
    font-size: 0.8rem;
    color: #666;
    line-height: 1.4;
  }

  /* Exam section */
  .exam-section {
    background: linear-gradient(180deg, var(--intro-bg) 0%, #e8eef5 100%);
  }

  .exam-step {
    background: #fff;
    border-radius: 1.25rem;
    padding: 1.75rem;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    height: 100%;
    position: relative;
    transition: all 0.3s;
  }

  .exam-step:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 86, 179, 0.12);
  }

  .exam-step-num {
    position: absolute;
    top: -12px;
    left: 1.5rem;
    background: var(--intro-orange);
    color: #fff;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 800;
  }

  .exam-step-icon {
    width: 56px;
    height: 56px;
    background: rgba(0, 86, 179, 0.1);
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
  }

  .exam-step-icon i {
    font-size: 1.5rem;
    color: var(--intro-blue);
  }

  .exam-step h5 {
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #1a1a2e;
  }

  .exam-step p {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.6;
    margin: 0;
  }

  .exam-rules-card {
    background: var(--intro-blue);
    color: #fff;
    border-radius: 1.25rem;
    padding: 2rem;
  }

  .exam-rules-card h5 {
    font-weight: 700;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .exam-rules-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .exam-rules-list li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.6rem 0;
    font-size: 0.95rem;
    line-height: 1.5;
    opacity: 0.95;
  }

  .exam-rules-list li i {
    color: var(--intro-orange);
    margin-top: 0.2rem;
    flex-shrink: 0;
  }

  /* CTA */
  .intro-cta {
    background: linear-gradient(135deg, var(--intro-orange) 0%, #e67e22 100%);
    color: #fff;
    padding: 3.5rem 0;
    text-align: center;
  }

  .intro-cta h3 {
    font-weight: 800;
    margin-bottom: 0.75rem;
  }

  .intro-cta p {
    opacity: 0.95;
    margin-bottom: 1.5rem;
    font-size: 1.05rem;
  }

  .btn-cta-white {
    background: #fff;
    color: var(--intro-orange);
    border: none;
    border-radius: 2rem;
    padding: 0.75rem 2.5rem;
    font-weight: 700;
    font-size: 1rem;
    transition: all 0.3s;
  }

  .btn-cta-white:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    color: #e67e22;
  }

  @media (max-width: 768px) {
    .intro-hero {
      padding: 3rem 0 4rem;
    }

    .hero-visual {
      margin-top: 2rem;
    }

    .intro-section {
      padding: 3rem 0;
    }
  }
</style>
@endsection

@section('content')
<div class="intro-page">

  {{-- Navbar --}}
  <nav class="intro-navbar navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('intro') }}">
        <img src="{{ asset('assets/images/icon-logo.png') }}" alt="Logo">
        <span class="fw-bold text-primary d-none d-sm-inline" style="color: var(--intro-blue) !important;">
          {{ config('center.short_name') }}
        </span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#introNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="introNav">
        <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
          <li class="nav-item"><a class="nav-link" href="#gioi-thieu">Giới thiệu</a></li>
          <li class="nav-item"><a class="nav-link" href="#khoa-hoc">Khóa học</a></li>
          <li class="nav-item"><a class="nav-link" href="#hang-chung-chi">Hạng</a></li>
          <li class="nav-item"><a class="nav-link" href="#ky-thi">Kỳ thi</a></li>
          <li class="nav-item"><a class="nav-link" href="#lien-he">Liên hệ</a></li>
          @auth
            <li class="nav-item ms-lg-2">
              <a href="{{ route('exams.index') }}" class="btn btn-intro-login">
                <i class="bi bi-journal-text me-1"></i> Ôn thi
              </a>
            </li>
          @else
            <li class="nav-item ms-lg-2">
              <a href="{{ route('login') }}" class="btn btn-intro-login">
                <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
              </a>
            </li>
          @endauth
        </ul>
      </div>
    </div>
  </nav>

  {{-- Hero --}}
  <section class="intro-hero">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-7">
          <div class="hero-badge">
            <i class="bi bi-geo-alt-fill"></i> Cần Thơ – Đồng bằng sông Cửu Long
          </div>
          <h1 class="hero-title">
            {{ config('center.name') }}
          </h1>
          <p class="hero-subtitle">{{ config('center.tagline') }}</p>
          <div class="hero-actions">
            @auth
              <a href="{{ route('exams.index') }}" class="btn btn-hero-primary">
                <i class="bi bi-play-circle me-1"></i> Bắt đầu ôn thi
              </a>
            @else
              <a href="{{ route('login') }}" class="btn btn-hero-primary">
                <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập ôn thi
              </a>
            @endauth
            <a href="#khoa-hoc" class="btn btn-hero-outline">
              <i class="bi bi-book me-1"></i> Xem khóa học
            </a>
          </div>
        </div>
        <div class="col-lg-5 hero-visual">
          <div class="hero-card">
            <div class="hero-stat-grid">
              @foreach(config('center.stats') as $stat)
                <div class="hero-stat-item">
                  <div class="value">{{ $stat['value'] }}</div>
                  <div class="label">{{ $stat['label'] }}</div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Giới thiệu trung tâm --}}
  <section class="intro-section" id="gioi-thieu">
    <div class="container">
      <div class="section-header">
        <span class="section-tag">Về chúng tôi</span>
        <h2 class="section-title">Thông tin Trung tâm</h2>
        <p class="section-desc">Uy tín đào tạo nghề đường thủy, đồng hành cùng học viên từ lớp học đến kỳ sát hạch</p>
      </div>
      <div class="row g-4">
        <div class="col-lg-7">
          <div class="about-card">
            @foreach(config('center.about') as $paragraph)
              <p class="text-muted mb-3" style="line-height: 1.8;">{{ $paragraph }}</p>
            @endforeach
            <div class="row g-3 mt-2">
              <div class="col-sm-6">
                <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: var(--intro-bg);">
                  <div class="rank-icon" style="width: 44px; height: 44px; margin: 0;">
                    <i class="bi bi-mortarboard" style="font-size: 1rem;"></i>
                  </div>
                  <div>
                    <strong class="d-block">Đào tạo chuẩn</strong>
                    <small class="text-muted">Theo quy định BGTVT</small>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: var(--intro-bg);">
                  <div class="rank-icon" style="width: 44px; height: 44px; margin: 0;">
                    <i class="bi bi-laptop" style="font-size: 1rem;"></i>
                  </div>
                  <div>
                    <strong class="d-block">Ôn thi online</strong>
                    <small class="text-muted">Mọi lúc, mọi nơi</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="about-card" id="lien-he">
            <h5 class="fw-bold mb-3"><i class="bi bi-building text-primary me-2"></i>Thông tin liên hệ</h5>
            <ul class="about-info-list">
              <li>
                <i class="bi bi-geo-alt-fill"></i>
                <div>
                  <strong>Địa chỉ</strong>
                  <span>{{ config('center.address') }}</span>
                </div>
              </li>
              <li>
                <i class="bi bi-telephone-fill"></i>
                <div>
                  <strong>Hotline</strong>
                  <span>{{ config('center.hotline') }}</span>
                </div>
              </li>
              <li>
                <i class="bi bi-envelope-fill"></i>
                <div>
                  <strong>Email</strong>
                  <span>{{ config('center.email') }}</span>
                </div>
              </li>
              <li>
                <i class="bi bi-clock-fill"></i>
                <div>
                  <strong>Giờ làm việc</strong>
                  <span>{{ config('center.working_hours') }}</span>
                </div>
              </li>
              <li>
                <i class="bi bi-globe"></i>
                <div>
                  <strong>Website</strong>
                  <span><a href="{{ config('center.website') }}" target="_blank" rel="noopener">{{ config('center.website') }}</a></span>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Khóa học --}}
  <section class="intro-section rank-section" id="khoa-hoc">
    <div class="container">
      <div class="section-header">
        <span class="section-tag">Chương trình đào tạo</span>
        <h2 class="section-title">Các khóa học</h2>
        <p class="section-desc">Đa dạng chương trình đào tạo phù hợp từng vị trí trên phương tiện thủy nội địa</p>
      </div>
      <div class="row g-4">
        @foreach(config('center.courses') as $course)
          <div class="col-md-6 col-lg-3">
            <div class="course-card">
              <img src="{{ asset($course['image']) }}" alt="{{ $course['title'] }}" class="course-card-img">
              <div class="course-card-body">
                <span class="course-code">{{ $course['code'] }}</span>
                <h5>{{ $course['title'] }}</h5>
                <p>{{ $course['description'] }}</p>
                <div class="course-meta">
                  <i class="bi bi-calendar3"></i> {{ $course['duration'] }}
                </div>
                <div class="course-ranks">
                  @foreach($course['ranks'] as $rank)
                    <span class="course-rank-badge">{{ $rank }}</span>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Hạng chứng chỉ --}}
  <section class="intro-section" id="hang-chung-chi">
    <div class="container">
      <div class="section-header">
        <span class="section-tag">Chứng chỉ chuyên môn</span>
        <h2 class="section-title">Các hạng đào tạo & Sát hạch</h2>
        <p class="section-desc">Hệ thống ôn thi hỗ trợ đầy đủ 13 hạng chứng chỉ theo quy định người lái phương tiện thủy</p>
      </div>

      @php
        $rankGroups = collect(config('center.ranks'))->groupBy('group');
      @endphp

      @foreach($rankGroups as $groupName => $ranks)
        <div class="mb-4">
          <h4 class="rank-group-title">{{ $groupName }}</h4>
          <div class="row g-3">
            @foreach($ranks as $rank)
              <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="rank-card">
                  <div class="rank-icon">
                    <i class="bi {{ $rank['icon'] }}"></i>
                  </div>
                  <div class="rank-code">{{ $rank['code'] }}</div>
                  <div class="rank-name">{{ $rank['name'] }}</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>
  </section>

  {{-- Kỳ thi --}}
  <section class="intro-section exam-section" id="ky-thi">
    <div class="container">
      <div class="section-header">
        <span class="section-tag">Hướng dẫn</span>
        <h2 class="section-title">{{ config('center.exam_info.title') }}</h2>
        <p class="section-desc">Quy trình ôn luyện và thi sát hạch tại hệ thống trực tuyến của trung tâm</p>
      </div>
      <div class="row g-4 mb-4">
        @foreach(config('center.exam_info.steps') as $index => $step)
          <div class="col-md-6 col-lg-3">
            <div class="exam-step">
              <span class="exam-step-num">{{ $index + 1 }}</span>
              <div class="exam-step-icon">
                <i class="bi {{ $step['icon'] }}"></i>
              </div>
              <h5>{{ $step['title'] }}</h5>
              <p>{{ $step['description'] }}</p>
            </div>
          </div>
        @endforeach
      </div>
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="exam-rules-card">
            <h5><i class="bi bi-info-circle-fill"></i> Quy định kỳ thi</h5>
            <ul class="exam-rules-list">
              @foreach(config('center.exam_info.rules') as $rule)
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  {{ $rule }}
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- CTA --}}
  <section class="intro-cta">
    <div class="container">
      <h3>Sẵn sàng ôn luyện cho kỳ sát hạch?</h3>
      <p>Đăng nhập bằng mã học viên để truy cập ngân hàng đề thi theo hạng của bạn</p>
      @auth
        <a href="{{ route('exams.index') }}" class="btn btn-cta-white">
          <i class="bi bi-journal-text me-1"></i> Vào danh sách đề thi
        </a>
      @else
        <a href="{{ route('login') }}" class="btn btn-cta-white">
          <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập ngay
        </a>
      @endauth
    </div>
  </section>

</div>
@endsection

<?php

return [
    'name' => 'Trung tâm dạy nghề Đường thủy Sông Hậu',
    'short_name' => 'TTDN Sông Hậu',
    'tagline' => 'Đào tạo – Sát hạch – Cấp chứng chỉ người lái phương tiện thủy nội địa',
    'address' => 'D30 Đường số 30, khu ĐTM Hưng Phú, Phường Cái Răng, TP. Cần Thơ',
    'hotline' => '0325 207 333',
    'email' => 'ttdn.songhau@gmail.com',
    'website' => 'https://songhau.bbq29.space/',
    'working_hours' => 'Thứ 2 – Thứ 7: 7h30 – 17h00',
    'established' => '2010',

    'about' => [
        'Trung tâm dạy nghề Đường thủy Sông Hậu là đơn vị đào tạo, bồi dưỡng nghiệp vụ và sát hạch cấp chứng chỉ chuyên môn cho người lái phương tiện thủy nội địa tại khu vực Đồng bằng sông Cửu Long.',
        'Hệ thống ôn thi trực tuyến giúp học viên luyện tập đề thi theo từng hạng, nắm vững kiến thức pháp luật, kỹ thuật lái tàu và an toàn giao thông đường thủy trước khi thi sát hạch chính thức.',
    ],

    'stats' => [
        ['value' => '15+', 'label' => 'Năm kinh nghiệm'],
        ['value' => '5000+', 'label' => 'Học viên đã đào tạo'],
        ['value' => '13', 'label' => 'Hạng chứng chỉ'],
        ['value' => '95%', 'label' => 'Tỷ lệ đậu sát hạch'],
    ],

    'courses' => [
        [
            'title' => 'Lái phương tiện thủy nội địa',
            'code' => 'LPT',
            'image' => 'assets/images/courses/lpt.svg',
            'description' => 'Đào tạo lái các loại phương tiện thủy nội địa, kỹ năng điều khiển và quy tắc giao thông đường thủy.',
            'duration' => '3 – 6 tháng',
            'ranks' => ['LPT', 'T4', 'T3', 'T2', 'T1'],
        ],
        [
            'title' => 'Thuyền trưởng – Thuyền viên',
            'code' => 'TM-TT',
            'image' => 'assets/images/courses/thuyen-truong.svg',
            'description' => 'Bồi dưỡng nghiệp vụ quản lý tàu, an toàn hàng hải và điều hành thuyền viên trên tàu thủy.',
            'duration' => '4 – 8 tháng',
            'ranks' => ['TM', 'TT'],
        ],
        [
            'title' => 'Máy tàu thủy',
            'code' => 'MAY',
            'image' => 'assets/images/courses/may-tau.svg',
            'description' => 'Đào tạo vận hành, bảo dưỡng máy tàu thủy nội địa theo các cấp độ từ M3 đến M1.',
            'duration' => '3 – 6 tháng',
            'ranks' => ['M3', 'M2', 'M1'],
        ],
        [
            'title' => 'Chứng chỉ chuyên môn bổ sung',
            'code' => 'CC',
            'image' => 'assets/images/courses/chung-chi.svg',
            'description' => 'Các khóa điều khiển cầu trục, an toàn vệ sinh lao động và an toàn xây dựng trên phương tiện thủy.',
            'duration' => '1 – 3 tháng',
            'ranks' => ['ĐKCT', 'ATVB', 'ATXD'],
        ],
    ],

    'ranks' => [
        ['code' => 'LPT', 'name' => 'Lái phương tiện thủy', 'group' => 'Lái tàu', 'icon' => 'bi-compass'],
        ['code' => 'T4', 'name' => 'Hạng T4', 'group' => 'Lái tàu', 'icon' => 'bi-water'],
        ['code' => 'T3', 'name' => 'Hạng T3', 'group' => 'Lái tàu', 'icon' => 'bi-water'],
        ['code' => 'T2', 'name' => 'Hạng T2', 'group' => 'Lái tàu', 'icon' => 'bi-water'],
        ['code' => 'T1', 'name' => 'Hạng T1', 'group' => 'Lái tàu', 'icon' => 'bi-water'],
        ['code' => 'TM', 'name' => 'Thuyền trưởng', 'group' => 'Quản lý tàu', 'icon' => 'bi-person-badge'],
        ['code' => 'TT', 'name' => 'Thuyền viên', 'group' => 'Quản lý tàu', 'icon' => 'bi-people'],
        ['code' => 'M3', 'name' => 'Máy hạng 3', 'group' => 'Máy tàu', 'icon' => 'bi-gear-wide-connected'],
        ['code' => 'M2', 'name' => 'Máy hạng 2', 'group' => 'Máy tàu', 'icon' => 'bi-gear-wide-connected'],
        ['code' => 'M1', 'name' => 'Máy hạng 1', 'group' => 'Máy tàu', 'icon' => 'bi-gear-wide-connected'],
        ['code' => 'ĐKCT', 'name' => 'Điều khiển cầu trục', 'group' => 'Chứng chỉ khác', 'icon' => 'bi-crane'],
        ['code' => 'ATVB', 'name' => 'An toàn vệ sinh', 'group' => 'Chứng chỉ khác', 'icon' => 'bi-shield-check'],
        ['code' => 'ATXD', 'name' => 'An toàn xây dựng', 'group' => 'Chứng chỉ khác', 'icon' => 'bi-building'],
    ],

    'exam_info' => [
        'title' => 'Kỳ thi sát hạch & Ôn luyện trực tuyến',
        'steps' => [
            [
                'icon' => 'bi-person-check',
                'title' => 'Đăng ký & Đăng nhập',
                'description' => 'Học viên nhận mã học viên từ trung tâm, đăng nhập hệ thống ôn thi để truy cập đề thi theo hạng đã đăng ký.',
            ],
            [
                'icon' => 'bi-book',
                'title' => 'Ôn tập theo hạng',
                'description' => 'Luyện tập từng câu hỏi, xem đáp án và giải thích. Hệ thống phân loại theo danh mục, mức độ và phần thi.',
            ],
            [
                'icon' => 'bi-clock-history',
                'title' => 'Thi thử có thời gian',
                'description' => 'Làm bài thi thử giống kỳ sát hạch thật: có giới hạn thời gian, bảng trả lời và chấm điểm tự động.',
            ],
            [
                'icon' => 'bi-award',
                'title' => 'Sát hạch chính thức',
                'description' => 'Đạt điểm yêu cầu trong các lần thi thử, học viên đủ điều kiện tham dự kỳ sát hạch do cơ quan có thẩm quyền tổ chức.',
            ],
        ],
        'rules' => [
            'Điểm đạt yêu cầu: tối thiểu 80/100 điểm (tùy hạng có thể khác biệt).',
            'Thời gian làm bài: 45 – 90 phút tùy loại đề thi.',
            'Hình thức: trắc nghiệm nhiều lựa chọn, có thể kèm hình ảnh minh họa.',
            'Nội dung: pháp luật giao thông đường thủy, kỹ thuật lái tàu, an toàn và cứu nạn.',
        ],
    ],
];

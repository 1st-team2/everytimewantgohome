<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Bar</title>
    <style>
        .progress-bar {
            width: 300px;
            height: 30px;
            border: 1px solid #ccc;
            position: relative;
        }
        .progress {
            height: 100%;
            background-color: #007bff;
            position: absolute;
            top: 0;
            left: 0;
            width: <?php echo $percentage ?>%;
            transition: width 0.5s ease;
        }
        .percentage {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 16px;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="progress-bar">
        <div class="progress"></div>
        <div class="percentage"><?php echo $percentage ?>%</div>
    </div>

    <script>
        // 초기 퍼센트 값 설정
        var percentage = <?php echo $percentage ?>;
        var progressBar = document.querySelector('.progress');

        function updateProgressBar() {
            // 게이지 업데이트
            progressBar.style.width = percentage + '%';
        }

        // 목표를 달성할 때마다 퍼센트를 업데이트하고 프로그레스 바를 업데이트합니다.
        // 예를 들어, 목표를 달성할 때마다 아래 함수를 호출하여 퍼센트를 업데이트할 수 있습니다.
        function updatePercentage(newPercentage) {
            percentage = newPercentage;
            updateProgressBar();
        }

        // 예제: 매번 목표를 10%씩 증가시킵니다.
        setInterval(function() {
            if (percentage < 100) {
                percentage += 10;
                updateProgressBar();
            }
        }, 2000); // 2초마다 실행
    </script>
</body>
</html>
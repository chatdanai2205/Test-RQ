<?php

$severname = "localhost";
$username = "root";
$password = "";
$dbname = "test_rq";
// เชื่อมต่อกับฐานข้อมูลด้วย PDO
try {
    $conn = new PDO("mysql:host=$severname;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL สำหรับรวมค่าจากคอลัมน์ที่เฉพาะเจาะจง
        // SQL สำหรับดึงข้อมูลทั้งหมดจากตาราง reserch
        $sql = "SELECT * FROM reserch";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    
        // ดึงผลลัพธ์
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // วนลูปเพื่อคำนวณผลรวมของแต่ละแถว
        foreach ($rows as $row) {
            // สมมติว่า column_name1, column_name2, column_name3 เป็นคอลัมน์ที่ต้องการรวม
            $total_score =  $row['question1'] +
                            $row['question2'] +
                            $row['question3'] +
                            $row['question4'] +
                            $row['question5'] +
                            $row['question6'] +
                            $row['question7'] +
                            $row['question8'] +
                            $row['question9'] +
                            $row['question10'] +
                            $row['question11'] +
                            $row['question12'] +
                            $row['question13'] +
                            $row['question14'] +
                            $row['question15'] +
                            $row['question16'] +
                            $row['question17'] +
                            $row['question18'] +
                            $row['question19'] +
                            $row['question20'];

            $total_score1 = $row['question1'] +
                            $row['question2'] +
                            $row['question3'] +
                            $row['question4'] +
                            $row['question5'] +
                            $row['question6'] +
                            $row['question7'] +
                            $row['question8'] +
                            $row['question9'] +
                            $row['question10'];

            $total_score2 = $row['question11'] +
                            $row['question12'] +
                            $row['question13'] +
                            $row['question14'] +
                            $row['question15'];

            $total_score3 = $row['question16'] +
                            $row['question17'] +
                            $row['question18'] +
                            $row['question19'] +
                            $row['question20'];
            
            $id = $row['id'];
                // เพิ่มข้อมูลลงในตาราง result
                $insertSql = "INSERT INTO result (id, total_score, pressure_tolerance, hope_and_support, overcoming_obstacles) VALUES (:id, :total_score, :pressure_tolerance, :hope_and_support, :overcoming_obstacles) ON DUPLICATE KEY UPDATE total_score = :total_score, pressure_tolerance = :pressure_tolerance, hope_and_support = :hope_and_support, overcoming_obstacles = :overcoming_obstacles";
                $insertStmt = $conn->prepare($insertSql);
                $insertStmt->bindParam(':id', $id);
                $insertStmt->bindParam(':total_score', $total_score);
                $insertStmt->bindParam(':pressure_tolerance', $total_score1);
                $insertStmt->bindParam(':hope_and_support', $total_score2);
                $insertStmt->bindParam(':overcoming_obstacles', $total_score3);
                $insertStmt->execute();
            }
         } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แบบประเมิน RQ</title>
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <!-- Custom CSS Link -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>
    <!--  header -->
    <header class="header">
        <a href="index.html" class="logo"><i class="fa-solid fa-stethoscope"></i> แบบประเมินพลังสุขภาพจิต</a>
    
        <nav class="navbar">
            <div id="close-navbar" class="fas fa-times"></div>
            <a href="homepage.php" class="animated-link">หน้าหลัก</a>
            <a href="about.php" class="animated-link">About</a>
            <a href="dashboard.php" class="animated-link">Dashboard</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
        </div>
    </header>
    <!--  header -->
    
    <div class="result">
        <div class="point">
            <h6 class="primary-text">ผลรวมคะแนนของท่าน คือ</h6>
            <div class="number">
                <h6 class="secondary-text1">
                    <?php
                        // ตรวจสอบค่า total_score1 และแสดงข้อความตามเกณฑ์
                        if ($total_score < 55) {
                            echo "<img src='image/bad.png' class='result-img' alt='Smile Image'>";
                            echo "<span style='color: red;'>$total_score </span>/ 80";
                        } elseif ($total_score >= 55 && $total_score <= 69) {
                            echo "<img src='image/smile.png' class='result-img' alt='Smile Image'>";
                            echo "<span style='color: orange;'>$total_score</span>/ 80";
                        } else {
                            echo "<img src='image/laugh.png' class='result-img' alt='Smile Image'>";
                            echo "<span style='color: green;'>$total_score</span>/ 80";
                        }
                    ?>
                </h6>
                <div class="primary-text">
                    <?php
                        if ($total_score < 55) {
                            echo "<span style='color: red;'>ต่ำกว่าเกณฑ์ปกติ</span>";
                        } elseif ($total_score >= 55 && $total_score <= 69) {
                            echo "<span style='color: orange;'>เกณฑ์ปกติ</span>";
                        } else {
                            echo "<span style='color: green;'>สูงกว่าเกณฑ์ปกติ</span>";
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="point">
            <div class="details">
                    <div class="rating">
                        <h6 class="primary-text1">ด้านความทนต่อแรงกดดัน </h6>
                        <div class="point-chart">
                            <div class="outer1">
                                <div class ="number" >
                                <div id="score1"></div>
                                    <?php 
                                        // ตรวจสอบค่า total_score1 และแสดงข้อความตามเกณฑ์
                                        if ($total_score1 < 23) {
                                            echo "<span style='color: red;'>$total_score1 </span> / 40";
                                        } elseif ($total_score1 >= 23 && $total_score1 <= 34) {
                                            echo "<span style='color: orange;'>$total_score1 </span> / 40";
                                        } else {
                                            echo "<span style='color: green;'>$total_score1 </span> / 40";
                                        }
                                    ?>
                                    <div id="criteria">
                                        <?php 
                                            // ตรวจสอบค่า total_score1 และแสดงข้อความตามเกณฑ์
                                            if ($total_score1 < 23) {
                                                echo "<span style='color: red;'>ต่ำกว่าเกณฑ์ปกติ</span>";
                                            } elseif ($total_score1 >= 23 && $total_score1 <= 34) {
                                                echo "<span style='color: orange;'>เกณฑ์ปกติ</span>";
                                            } else {
                                                echo "<span style='color: green;'>สูงกว่าเกณฑ์ปกติ</span>";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rating">
                        <h6 class="primary-text1">ด้านการมีหวังและกำลังใจ</h6>
                        <div class="point-chart">
                            <div class="outer2">
                                <div class ="number">
                                    <div id="score2"></div>
                                    <?php 
                                        // ตรวจสอบค่า total_score1 และแสดงข้อความตามเกณฑ์
                                        if ($total_score2 < 14) {
                                            echo "<span style='color: red;'>$total_score2 </span> / 20";
                                        } elseif ($total_score2 >= 14 && $total_score2 <= 19) {
                                            echo "<span style='color: orange;'>$total_score2 </span> / 20";
                                        } else {
                                            echo "<span style='color: green;'>$total_score2 </span> / 20";
                                        }
                                    ?>
                                    <div id="criteria">
                                        <?php 
                                            // ตรวจสอบค่า total_score1 และแสดงข้อความตามเกณฑ์
                                            if ($total_score2 < 14) {
                                                echo "<span style='color: red;'>ต่ำกว่าเกณฑ์ปกติ</span>";
                                            } elseif ($total_score2 >= 14 && $total_score2 <= 19) {
                                                echo "<span style='color: orange;'>เกณฑ์ปกติ</span>";
                                            } else {
                                                echo "<span style='color: green;'>สูงกว่าเกณฑ์ปกติ</span>";
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="rating">
                        <h6 class="primary-text1">ด้านการต่อสู้เอาชนะอุปสรรค</h6>
                        <div class="point-chart">
                            <div class="outer3">
                                <div class ="number">
                                    <div id="score3"></div>
                                    <?php 
                                        // ตรวจสอบค่า total_score1 และแสดงข้อความตามเกณฑ์
                                        if ($total_score3 < 13) {
                                            echo "<span style='color: red; '>$total_score3 </span> / 20";
                                        } elseif ($total_score3 >= 13 && $total_score3 <= 18) {
                                            echo "<span style='color: orange;'>$total_score3 </span> / 20";
                                        } else {
                                            echo "<span style='color: green;'>$total_score3 </span> / 20";
                                        }
                                    ?>
                                <div id="criteria">
                                    <?php 
                                        // ตรวจสอบค่า total_score1 และแสดงข้อความตามเกณฑ์
                                        if ($total_score3 < 13) {
                                            echo "<span style='color: red;'>ต่ำกว่าเกณฑ์ปกติ</span>";
                                        } elseif ($total_score3 >= 13 && $total_score3 <= 18) {
                                            echo "<span style='color: orange;'>เกณฑ์ปกติ</span>";
                                        } else {
                                            echo "<span style='color: green;'>สูงกว่าเกณฑ์ปกติ</span>";
                                        }
                                    ?> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
        <div class="button" id="recomand-btn">
            <button class="recomand-btn"><i class="fa-solid fa-user-doctor"></i>&nbsp;คลิ๊กเพื่อดูคำแนะนำ</button>
        </div>
    </div>  
    <div class="recomand">              
        <div class="popup-recomand">
            <h2>คำแนะนำของท่าน</h2>
            <div id="goo">
            <?php 
                // ตรวจสอบค่า total_score1 และแสดงข้อความตามเกณฑ์
                if ($total_score >= 54 && $total_score <= 69 ) {
                    echo '<i class="fa-solid fa-map-pin"></i> ท่านจัดอยู่ในกลุ่มคนที่มีพลังสุขภาพจิตปกติทั่วไป ท่านอาจพัฒนาตนเอง โดยการแสวงหาความรู้เพื่อ เสริมสร้างพลังสุขภาพจิตให้คงอยู่<br>';
                }elseif ($total_score > 69){
                    echo '<i class="fa-solid fa-map-pin"></i> ท่านจัดอยู่ในกลุ่มคนที่มีพลังพลังสุขภาพจิตดีเยี่ยมขอให้ท่านรักษาศักยภาพด้านนี้ไว้<br>';
                }elseif ($total_score < 54){  
                    if ($total_score1 < 23 ) {
                        echo    '<i class="fa-solid fa-map-pin"></i> <strong> ด้านความทนต่อแรงกดดัน</strong> <br>
                            &nbsp;  ท่านสามารถพัฒนาศักยภาพด้านนี้ได้โดยฝึกควบคุมอารมณ์ตนเองให้มี 
                            สติและสงบโดยเริ่มต้นจากการควบคุมอารมณ์เมื่อเผชิญกับสถานการณ์เล็กน้อย ๆ ที่ทำให้เกิดความเครียดความผิดหวัง 
                            ฝึกหายใจเข้าออกช้า ๆ ลึก ๆ และคิดถึงสิ่งที่ยึดเหนี่ยวทางใจ<br>'; 
                    }if ($total_score2 < 13 ) {    
                        echo '<i class="fa-solid fa-map-pin"></i> <strong> ด้านกำลังใจ</strong> <br>
                            &nbsp;  ท่านสามารถพัฒนาศักยภาพด้านนี้ได้โดยคิดถึงสิ่งดีดีที่ท่านมีอยู่หมั่นพูดให้กำลังใจตนเอง 
                            เช่น เราต้องผ่านพ้นไปได้ชีวิตย่อมมีขึ้นมีลงคิดถึงโอกาสข้างหน้าหากฝ่าฟันจุดนี้ไปได้<br>';
                    }if ($total_score3 < 11 ) {
                        echo '<i class="fa-solid fa-map-pin"></i> <strong> ด้านการต่อสู้เอาชนะอุปสรรค</strong> <br>
                            &nbsp;  ท่านสามารถพัฒนาศักยภาพด้านนี้ได้โดยฝึกคิดหาทางออกในการแก้บัญหาเริ่มจากปัญหาเล็ก ๆ น้อย ๆ 
                            หาทางออกหาข้อดีข้อเสียในแต่ละวิธีการเลือกวิธีการที่ดีที่สุดและคิดหาวิธีการสำรองไว้เผื่อวิธีที่เลือกใช้ไม่ได้ผลการแก้ไขปัญหาได้สำเร็จจะช่วยให้ท่านเห็นว่าการแก้ไขปัญหาไม่ใช่เรื่องยาก 
                            และมีทักษะที่ดีในการแก้ปัญหาได้<br>';
                    }
                }
            ?> 
            </div>
            <div class="btn-recomand">
                <button class="recomand-btn exit-btn">Exit</button>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>
    <script src="js/recommand.js"></script>
    <script>
    // รับค่าจาก PHP ด้วยการแทรกในโค้ด JavaScript
    let totalScore1 = <?php echo $total_score1; ?>; // รับค่า $total_score1 จาก PHP
    let totalScore2 = <?php echo $total_score2; ?>; // รับค่า $total_score2 จาก PHP
    let totalScore3 = <?php echo $total_score3; ?>; // รับค่า $total_score3 จาก PHP

    // คำนวณ progressEndValue โดยใช้สูตร progressEndValue = totalScore * (100 / 40)
    let progressEndValue1 = totalScore1 * (100 / 40);
    let progressEndValue2 = totalScore2 * (100 / 20);
    let progressEndValue3 = totalScore3 * (100 / 20);

    // เลือก element ที่จะแสดงผล
    let circularProgress1 = document.querySelector(".outer1"),
        progressValue1 = document.querySelector("#score1"),
        circularProgress2 = document.querySelector(".outer2"),
        progressValue2 = document.querySelector("#score2"),
        circularProgress3 = document.querySelector(".outer3"),
        progressValue3 = document.querySelector("#score3");

    // ฟังก์ชันเลือกสีตามคะแนน
    function getColor(score, thresholds) {
        if (score < thresholds.low) {
            return "#FF0000"; // สีแดง
        } else if (score >= thresholds.low && score <= thresholds.high) {
            return "#FFD700"; // สีเหลือง
        } else {
            return "#32CD32"; // สีเขียว
        }
    }

    // ฟังก์ชันสำหรับเพิ่มค่าความคืบหน้า
    function startProgress(circularProgress, progressValue, score, progressEndValue, thresholds) {
        let progressStartValue = 0; // เริ่มค่าความคืบหน้าที่ 0
        let speed = 20; // ความเร็วในการเพิ่มค่า (มิลลิวินาที)

        let progress = setInterval(() => {
            // เพิ่มค่าความคืบหน้า
            progressStartValue++;

            // เลือกสี
            let color = getColor(score, thresholds);

            // อัปเดตสีของวงกลม
            circularProgress.style.background = `conic-gradient(${color} ${progressStartValue * 3.6}deg, #ededed 0deg)`;

            // หยุดการทำงานเมื่อค่าความคืบหน้าและการแสดงผลถึงเป้าหมาย
            if (progressStartValue >= progressEndValue) {
                clearInterval(progress);
            }
        }, speed);
    }

    // เริ่มการทำงานของแต่ละโปรเกรส
    startProgress(circularProgress1, progressValue1, totalScore1, progressEndValue1, { low: 23, high: 34 });
    startProgress(circularProgress2, progressValue2, totalScore2, progressEndValue2, { low: 14, high: 19 });
    startProgress(circularProgress3, progressValue3, totalScore3, progressEndValue3, { low: 13, high: 18 });
</script>



</body>
</html>
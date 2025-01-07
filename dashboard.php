<?php
require("dbconnect.php");
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_rq";

try {
    // เชื่อมต่อกับฐานข้อมูล
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ดึงข้อมูลสำหรับกราฟที่ 1: การแจกแจงคะแนนรวม และคะแนนในแต่ละด้าน
    $sqlall = "SELECT 
                SUM(CASE WHEN total_score < 55 THEN 1 ELSE 0 END) as below_normal_total_score,
                SUM(CASE WHEN total_score >= 55 AND total_score <= 69 THEN 1 ELSE 0 END) as normal_total_score,
                SUM(CASE WHEN total_score > 69 THEN 1 ELSE 0 END) as above_normal_total_score,
                
                SUM(CASE WHEN pressure_tolerance < 23 THEN 1 ELSE 0 END) as below_normal_pressure,
                SUM(CASE WHEN pressure_tolerance >= 23 AND pressure_tolerance <= 34 THEN 1 ELSE 0 END) as normal_pressure,
                SUM(CASE WHEN pressure_tolerance > 34 THEN 1 ELSE 0 END) as above_normal_pressure,
                
                SUM(CASE WHEN hope_and_support < 14 THEN 1 ELSE 0 END) as below_normal_hope,
                SUM(CASE WHEN hope_and_support >= 14 AND hope_and_support <= 19 THEN 1 ELSE 0 END) as normal_hope,
                SUM(CASE WHEN hope_and_support > 19 THEN 1 ELSE 0 END) as above_normal_hope,
                
                SUM(CASE WHEN overcoming_obstacles < 13 THEN 1 ELSE 0 END) as below_normal_obstacles,
                SUM(CASE WHEN overcoming_obstacles >= 13 AND overcoming_obstacles <= 18 THEN 1 ELSE 0 END) as normal_obstacles,
                SUM(CASE WHEN overcoming_obstacles > 18 THEN 1 ELSE 0 END) as above_normal_obstacles
            FROM result";
    $stmt = $conn->prepare($sqlall);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // ดึงข้อมูลสำหรับกราฟที่ 2: การแจกแจงจำนวนผู้ตอบแบบสอบถามตามเพศ
    $sqlGender = "SELECT gender, COUNT(*) as count FROM user GROUP BY gender";
    $stmt = $conn->prepare($sqlGender);
    $stmt->execute();
    $gender = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงข้อมูลสำหรับกราฟที่ 3: การแจกแจงจำนวนผู้ตอบแบบสอบถามตามชั้นปี
    $sqlYear = "SELECT year, COUNT(*) as count FROM user GROUP BY year";
    $stmt = $conn->prepare($sqlYear);
    $stmt->execute();
    $Year = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงข้อมูลสำหรับกราฟที่ 4: การแจกแจงจำนวนผู้ตอบแบบสอบถามตามสาขา
    $sqlDepartment = "SELECT department, COUNT(*) as count FROM user GROUP BY department";
    $stmt = $conn->prepare($sqlDepartment);
    $stmt->execute();
    $department = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงข้อมูลสำหรับกราฟที่ 5: ค่าเฉลี่ยคะแนนในแต่ละด้านแยกตามเพศ
    $sqlGender = "SELECT u.gender,
                        AVG(r.pressure_tolerance) AS avg_pressure_tolerance,
                        AVG(r.hope_and_support) AS avg_hope_and_support,
                        AVG(r.overcoming_obstacles) AS avg_overcoming_obstacles
                    FROM user u
                    JOIN result r ON u.id = r.id
                    GROUP BY u.gender
                    ORDER BY 
                        CASE 
                            WHEN u.gender = 'ชาย' THEN 1
                            WHEN u.gender = 'หญิง' THEN 2
                            WHEN u.gender = 'LGBTQA+' THEN 3
                        END";
    $stmt = $conn->prepare($sqlGender);
    $stmt->execute();
    $GenderAVG = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงข้อมูลสำหรับกราฟที่ 6: ค่าเฉลี่ยคะแนนในแต่ละด้านแยกตามชั้นปี
    $sqlYear = "SELECT u.year,
                        AVG(r.pressure_tolerance) AS avg_pressure_tolerance,
                        AVG(r.hope_and_support) AS avg_hope_and_support,
                        AVG(r.overcoming_obstacles) AS avg_overcoming_obstacles
                    FROM user u
                    JOIN result r ON u.id = r.id
                    GROUP BY u.year
                    ORDER BY u.year ASC";
    $stmt = $conn->prepare($sqlYear);
    $stmt->execute();
    $YearAVG = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงข้อมูลสำหรับกราฟที่ 7: ค่าเฉลี่ยคะแนนในแต่ละด้านแยกตามสาขา
    $sqlDepartment = "SELECT u.department,
                            AVG(r.pressure_tolerance) AS avg_pressure_tolerance,
                            AVG(r.hope_and_support) AS avg_hope_and_support,
                            AVG(r.overcoming_obstacles) AS avg_overcoming_obstacles
                        FROM user u
                        JOIN result r ON u.id = r.id
                        GROUP BY u.department
                        ORDER BY u.department ASC";
    $stmt = $conn->prepare($sqlDepartment);
    $stmt->execute();
    $DepartmentAVG = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงข้อมูลสำหรับกราฟที่ 8: ค่าเฉลี่ยคะแนนในแต่ละด้านแยกตามการมีโรคประจำตัว
    $sqlChronic_disease = "SELECT u.chronic_disease,
                    AVG(r.pressure_tolerance) AS avg_pressure_tolerance,
                    AVG(r.hope_and_support) AS avg_hope_and_support,
                    AVG(r.overcoming_obstacles) AS avg_overcoming_obstacles
                FROM user u
                JOIN result r ON u.id = r.id
                GROUP BY u.chronic_disease
                ORDER BY u.chronic_disease ASC";
    $stmt = $conn->prepare($sqlChronic_disease);
    $stmt->execute();
    $Chronic_diseaseAVG = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงข้อมูลสำหรับกราฟที่ 9: ค่าเฉลี่ยคะแนนในแต่ละด้านแยกตามสถานะครอบครัว
    $sqlFam_status = "SELECT u.fam_status,
                        AVG(r.pressure_tolerance) AS avg_pressure_tolerance,
                        AVG(r.hope_and_support) AS avg_hope_and_support,
                        AVG(r.overcoming_obstacles) AS avg_overcoming_obstacles
                    FROM user u
                    JOIN result r ON u.id = r.id
                    GROUP BY u.fam_status
                    ORDER BY u.fam_status ASC";
    $stmt = $conn->prepare($sqlFam_status);
    $stmt->execute();
    $Fam_statusAVG = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงข้อมูลสำหรับกราฟที่ 10: ค่าเฉลี่ยคะแนนในแต่ละด้านแยกตามอาชีพครอบครัว
    $sqlFam_careerAVG = "SELECT u.fam_career,
            AVG(r.pressure_tolerance) AS avg_pressure_tolerance,
            AVG(r.hope_and_support) AS avg_hope_and_support,
            AVG(r.overcoming_obstacles) AS avg_overcoming_obstacles
        FROM user u
        JOIN result r ON u.id = r.id
        GROUP BY u.fam_career
        ORDER BY u.fam_career ASC";
    $stmt = $conn->prepare($sqlFam_careerAVG);
    $stmt->execute();
    $Fam_careerAVG = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงข้อมูลสำหรับกราฟที่ 11: ค่าเฉลี่ยคะแนนในแต่ละด้านแยกตามรายได้ครอบครัว
    $sqlFam_earnAVG = "SELECT u.fam_earn,
                            AVG(r.pressure_tolerance) AS avg_pressure_tolerance,
                            AVG(r.hope_and_support) AS avg_hope_and_support,
                            AVG(r.overcoming_obstacles) AS avg_overcoming_obstacles
                        FROM user u
                        JOIN result r ON u.id = r.id
                        GROUP BY u.fam_earn
                        ORDER BY u.fam_earn ASC";
    $stmt = $conn->prepare($sqlFam_earnAVG);
    $stmt->execute();
    $Fam_earnAVG = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงข้อมูลสำหรับกราฟที่ 12: ค่าเฉลี่ยคะแนนในแต่ละด้านแยกตามเกรดเฉลี่ย
    $sqlGpaAVG = "SELECT u.gpa,
                        AVG(r.pressure_tolerance) AS avg_pressure_tolerance,
                        AVG(r.hope_and_support) AS avg_hope_and_support,
                        AVG(r.overcoming_obstacles) AS avg_overcoming_obstacles
                    FROM user u
                    JOIN result r ON u.id = r.id
                    GROUP BY u.gpa
                    ORDER BY u.gpa ASC";
    $stmt = $conn->prepare($sqlGpaAVG);
    $stmt->execute();
    $GpaAVG = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ดึงข้อมูลสำหรับกราฟที่ 13: ค่าเฉลี่ยคะแนนในแต่ละด้านแยกตามหลักสูตร
    $sqlCourseAVG = "SELECT u.course,
                        AVG(r.pressure_tolerance) AS avg_pressure_tolerance,
                        AVG(r.hope_and_support) AS avg_hope_and_support,
                        AVG(r.overcoming_obstacles) AS avg_overcoming_obstacles
                    FROM user u
                    JOIN result r ON u.id = r.id
                    GROUP BY u.course
                    ORDER BY u.course ASC";
    $stmt = $conn->prepare($sqlCourseAVG);
    $stmt->execute();
    $CourseAVG = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <div class="dashboard">
        <div class="message-box">
            <div class="box" >
                <div>
                    <br>
                </div>
                <h1>
                    <?php
                        $sql = "SELECT COUNT(*) as id FROM reserch";
                        $query = $conn->prepare($sql);
                        $query->execute();
                        $fetch = $query->fetch();
                    ?>
                    <?= $fetch['id'] ," คน";?>
                    <div class="primary-text1">จำนวนผู้ทำแบบประเมินทั้งหมด</div>
                </h1>
            </div>
            <div class="box" style="background: #ff6384; color: #fff;">
                <h1>
                    <div class="primary-text1"style="color: #fff;">
                        <?php 
                            echo "จำนวน ". $result['below_normal_total_score']. " คน";
                        ?>
                    </div>
                    <?php
                        if ($result) {
                            $total_count = $result['below_normal_total_score'] + $result['normal_total_score'] + $result['above_normal_total_score'];
                            echo ($total_count > 0) ? number_format(($result['below_normal_total_score'] / $total_count) * 100) . " %" : "ไม่มีข้อมูลเพื่อแสดงผล";
                        }
                    ?>
                    <div class="primary-text1" style="color: #fff;">ต่ำกว่าเกณฑ์ปกติ</div>
                </h1>
            </div>
            <div class="box" style="background: rgba(255, 204, 0, 1); color: #fff;";>
                <h1>
                    <div class="primary-text1"style="color: #fff;">
                        <?php 
                            echo "จำนวน ". $result['normal_total_score']. " คน";
                        ?>
                    </div>
                    <?php
                        if ($result) {
                            $total_count = $result['below_normal_total_score'] + $result['normal_total_score'] + $result['above_normal_total_score'];
                            echo ($total_count > 0) ? number_format(($result['normal_total_score'] / $total_count) * 100) . " %" : "ไม่มีข้อมูลเพื่อแสดงผล";
                        }
                    ?>
                    <div class="primary-text1" style="color: #fff;">เกณฑ์ปกติ</div>
                </h1>
            </div>
            <div class="box" style="background: rgba(75, 192, 19, 1); color: #fff;";>
                <h1>
                    <div class="primary-text1"style="color: #fff;">
                        <?php 
                            echo "จำนวน ". $result['above_normal_total_score']. " คน";
                        ?>
                    </div>
                    <?php
                        if ($result) {
                            $total_count = $result['below_normal_total_score'] + $result['normal_total_score'] + $result['above_normal_total_score'];
                            echo ($total_count > 0) ? number_format(($result['above_normal_total_score'] / $total_count) * 100) . " %" : "ไม่มีข้อมูลเพื่อแสดงผล";
                        }
                    ?>
                    <div class="primary-text1" style="color: #fff;">สูงกว่าเกณฑ์ปกติ</div>
                </h1>
            </div>
        </div>
        <div class="message-box">
            <div class="box">
                <div class="containered">
                        <?php
                            $votes = [
                                (int)$result['below_normal_total_score'],
                                (int)$result['normal_total_score'],
                                (int)$result['above_normal_total_score']
                            ];
                            $labels = ['ต่ำกว่าเกณฑ์ปกติ', 'เกณฑ์ปกติ', 'สูงกว่าเกณฑ์ปกติ'];
                        ?>
                        <script>
                            const chartData = {
                                labels: <?php echo json_encode($labels, JSON_UNESCAPED_UNICODE); ?>,
                                data: <?php echo json_encode($votes); ?>
                            };
                        </script>
                    <canvas id="pie_chart"></canvas>
                </div>
            </div>
            <div class="box">
                <div class="containered">
                        <?php
                            $votes = ['male' => 0, 'female' => 0, 'LGBTQA+' => 0];
                            foreach ($gender as $genders) {
                                if ($genders['gender'] === 'ชาย') {
                                    $votes['male'] = $genders['count'];
                                } elseif ($genders['gender'] === 'หญิง') {
                                    $votes['female'] = $genders['count'];
                                } elseif ($genders['gender'] === 'LGBTQA+') {
                                    $votes['LGBTQA+'] = $genders['count'];
                                }
                            }
                            $dataGender = [$votes['male'], $votes['female'], $votes['LGBTQA+']];
                        ?>
                        <script>
                            const chartDatagender = {
                                labelsGender: ['ชาย', 'หญิง','LGBTQA+'],
                                dataGender: <?php echo json_encode($dataGender); ?>
                            };
                        </script>
                    <canvas id="pie_chart2"></canvas>
                </div>
            </div>
            <div class="box">
                <div class="containered">
                        <?php
                            $sqlYear = "SELECT year, COUNT(*) as count FROM user GROUP BY year";
                            $stmt = $conn->prepare($sqlYear);
                            $stmt->execute();
                            $Year = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $labelsYear = [];
                            $dataYear = [];
                        
                            foreach ($Year as $row) {
                                $labelsYear[] = $row['year']; // เก็บชื่อปี
                                $dataYear[] = (int) $row['count']; // เก็บจำนวน (แปลงเป็น integer)
                            }
                        ?>
                        <script>
                            const chartDataYear = {
                                labelsYear: <?php echo json_encode($labelsYear, JSON_UNESCAPED_UNICODE); ?>, // ส่งข้อมูล labels ไปยัง JavaScript
                                dataYear: <?php echo json_encode($dataYear); ?> // ส่งข้อมูล data ไปยัง JavaScript
                            };
                        </script>
                    <canvas id="pie_chart3"></canvas>
                </div>
            </div>
            <div class="box">
                <div class="containered">
                    <?php
                        // สร้าง array สำหรับเก็บข้อมูลนับจำนวนของแต่ละภาควิชา
                        $votesDepartments = [
                            'ภาควิชาเคมี' => 0,
                            'ภาควิชาชีววิทยา' => 0,
                            'ภาควิชาคณิตศาสตร์' => 0,
                            'ภาควิชาฟิสิกส์' => 0,
                            'ภาควิชาวิทยาการคอมพิวเตอร์' => 0,
                            'ภาควิชาสถิติ' => 0,
                            'ศูนย์วิเคราะห์ข้อมูลดิจิทัลอัจฉริยะพระจอมเกล้าลาดกระบัง' => 0
                        ];

                        // ดึงข้อมูลจากฐานข้อมูลแล้ววนลูปเพิ่มค่าลงใน array
                        foreach ($department as $dept) {
                            $name = $dept['department']; // ชื่อภาควิชา
                            $count = $dept['count']; // จำนวนที่นับได้
                            if (array_key_exists($name, $votesDepartments)) {
                                $votesDepartments[$name] = $count;
                            }
                        }

                        // แปลงข้อมูลสำหรับ JavaScript
                        $dataDepartments = array_values($votesDepartments); // ค่า (จำนวน)
                        $labelsDepartments = array_keys($votesDepartments); // ชื่อภาควิชา
                        ?>
                      <script>
                        const chartDataDepartments = {
                            labelsDepartments: <?php echo json_encode($labelsDepartments, JSON_UNESCAPED_UNICODE); ?>,
                            dataDepartments: <?php echo json_encode($dataDepartments); ?>
                        };
                    </script>
                    <canvas id="pie_chart4"></canvas>
                </div>
            </div>
        </div>
        <div class="message-box">
            <div class="box">
                <div class="containered">
                    <script>
                        const gender = <?php echo json_encode($GenderAVG); ?>;
                    </script>
                    <canvas id="pie_chart5"></canvas>
                </div>
            </div>
            <div class="box">
                <div class="containered">
                    <script>
                        const year = <?php echo json_encode($YearAVG, JSON_UNESCAPED_UNICODE); ?>;
                    </script>
                    <canvas id="pie_chart6"></canvas>
                </div>
            </div>
            <div class="box">
                <div class="containered">
                    <script>
                        const department = <?php echo json_encode($DepartmentAVG, JSON_UNESCAPED_UNICODE); ?>;
                    </script>
                    <canvas id="pie_chart7"></canvas>
                </div>
            </div>
        </div>
        <div class="message-box">
            <div class="box">
                <div class="containered">
                    <script>
                        const chronic_disease = <?php echo json_encode($Chronic_diseaseAVG, JSON_UNESCAPED_UNICODE); ?>;
                    </script>
                    <canvas id="pie_chart8"></canvas>
                </div>
            </div>
            <div class="box">
                <div class="containered">
                    <script>
                        const fam_status = <?php echo json_encode($Fam_statusAVG, JSON_UNESCAPED_UNICODE); ?>;
                    </script>
                    <canvas id="pie_chart9"></canvas>
                </div>
            </div>
            <div class="box">
                <div class="containered">
                    <script>
                        const fam_career = <?php echo json_encode($Fam_careerAVG, JSON_UNESCAPED_UNICODE); ?>;
                    </script>
                    <canvas id="pie_chart10"></canvas>
                </div>
            </div>
        </div>
        <div class="message-box">
            <div class="box">
                <div class="containered">
                    <script>
                        const fam_earn = <?php echo json_encode($Fam_earnAVG, JSON_UNESCAPED_UNICODE); ?>;
                    </script>
                    <canvas id="pie_chart11"></canvas>
                </div>
            </div>
            <div class="box">
                <div class="containered">
                    <script>
                        const gpa = <?php echo json_encode($GpaAVG, JSON_UNESCAPED_UNICODE); ?>;
                    </script>
                    <canvas id="pie_chart12"></canvas>
                </div>
            </div>
            <div class="box">
                <div class="containered">
                    <script>
                        const course = <?php echo json_encode($CourseAVG, JSON_UNESCAPED_UNICODE); ?>;
                    </script>
                    <canvas id="pie_chart13"></canvas>
                </div>
            </div>
        </div>  
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <script src="js/chart1.js"></script>
    <script src="js/chart2.js"></script>
    <script src="js/chart3.js"></script>
    <script src="js/chart4.js"></script>
    <script src="js/chart5.js"></script>
    <script src="js/chart6.js"></script>
    <script src="js/chart7.js"></script>
    <script src="js/chart8.js"></script>
    <script src="js/chart9.js"></script>
    <script src="js/chart10.js"></script>
    <script src="js/chart11.js"></script>
    <script src="js/chart12.js"></script>
    <script src="js/chart13.js"></script>
    <script src="js/script.js"></script>
</body>
</html>

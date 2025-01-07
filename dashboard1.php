<?php
require("dbconnect.php");
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_rq";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL สำหรับดึงข้อมูลรวม
    $sql = "SELECT 
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
    
    $sql = "SELECT 
                SUM(CASE WHEN total_score < 55 THEN 1 ELSE 0 END) AS below_normal_total_score,
                SUM(CASE WHEN total_score >= 55 AND total_score <= 69 THEN 1 ELSE 0 END) AS normal_total_score,
                SUM(CASE WHEN total_score > 69 THEN 1 ELSE 0 END) AS above_normal_total_score,
                gender, department
        FROM user
        INNER JOIN result ON user.id = result.id
        GROUP BY gender, department";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบว่ามีผลลัพธ์หรือไม่
    $sql1 = "SELECT 
                SUM(CASE WHEN total_score < 55 THEN 1 ELSE 0 END) AS below_normal,
                SUM(CASE WHEN total_score >= 55 AND total_score <= 69 THEN 1 ELSE 0 END) AS normal,
                SUM(CASE WHEN total_score > 69 THEN 1 ELSE 0 END) AS above_normal
            FROM result";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->execute();
    $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
    
    // ขั้นตอนที่ 2: ดึงข้อมูลเพศ
    $sql2 = "SELECT gender, COUNT(*) as count FROM user GROUP BY gender";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->execute();
    $genders = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    $sqlUser = "SELECT id, gender FROM user";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->execute();
    $users = $stmtUser->fetchAll(PDO::FETCH_ASSOC);
    
    // ขั้นตอนที่ 3: ดึงข้อมูล chronic_disease และค่าเฉลี่ย
    $sqlChronic = "SELECT 
                u.chronic_disease,
                AVG(r.pressure_tolerance) AS avg_pressure_tolerance,
                AVG(r.hope_and_support) AS avg_hope_and_support,
                AVG(r.overcoming_obstacles) AS avg_overcoming_obstacles
            FROM 
                user u
            JOIN 
                result r ON u.id = r.id
            GROUP BY 
                u.chronic_disease";

    $stmtChronic = $conn->prepare($sqlChronic);
    $stmtChronic->execute();
    $chronicData = $stmtChronic->fetchAll(PDO::FETCH_ASSOC);

// สร้างโครงสร้างข้อมูลในรูปแบบที่ต้องการ
$chronicArray = [];
foreach ($chronicData as $row) {
$chronicArray[] = [
    'chronic_disease' => $row['chronic_disease'],
    'avg_pressure_tolerance' => $row['avg_pressure_tolerance'],
    'avg_hope_and_support' => $row['avg_hope_and_support'],
    'avg_overcoming_obstacles' => $row['avg_overcoming_obstacles'],
];
}

    // ดึงข้อมูลภาควิชา
    $sqlDepartments = "SELECT department, id FROM user";
    $stmtDepartments = $conn->prepare($sqlDepartments);
    $stmtDepartments->execute();
    $departments = $stmtDepartments->fetchAll(PDO::FETCH_ASSOC);
    
    $data4 = [
        'ชาย' => ['pressure_tolerance' => 0, 'hope_and_support' => 0, 'overcoming_obstacles' => 0, 'count' => 0],
        'หญิง' => ['pressure_tolerance' => 0, 'hope_and_support' => 0, 'overcoming_obstacles' => 0, 'count' => 0],
        'LGBTQA+' => ['pressure_tolerance' => 0, 'hope_and_support' => 0, 'overcoming_obstacles' => 0, 'count' => 0],
    ];
    
    foreach ($users as $user) {
        $sqlResult = "SELECT pressure_tolerance, hope_and_support, overcoming_obstacles FROM result WHERE id = :id";
        $stmtResult = $conn->prepare($sqlResult);
        $stmtResult->bindParam(':id', $user['id']);
        $stmtResult->execute();
        
        $result3 = $stmtResult->fetch(PDO::FETCH_ASSOC);
        
        if ($result3) {
            $gender = $user['gender'];
            $data4[$gender]['count']++; // เพิ่มจำนวนผู้ใช้
            
            // สะสมคะแนนสำหรับแต่ละด้าน
            $data4[$gender]['pressure_tolerance'] += $result3['pressure_tolerance'];
            $data4[$gender]['hope_and_support'] += $result3['hope_and_support'];
            $data4[$gender]['overcoming_obstacles'] += $result3['overcoming_obstacles'];
        }
    }
    
    // คำนวณค่าเฉลี่ย
    foreach ($data4 as $gender => $values) {
        if ($values['count'] > 0) {
            $data4[$gender]['pressure_tolerance'] /= $values['count'];
            $data4[$gender]['hope_and_support'] /= $values['count'];
            $data4[$gender]['overcoming_obstacles'] /= $values['count'];
        }
    }
    
    // สร้างโครงสร้างข้อมูลในรูปแบบที่ต้องการ
    $data4Array = [];
    foreach ($data4 as $gender => $values) {
        $data4Array[] = [
            'x' => $gender,
            'pressure_tolerance' => $values['pressure_tolerance'],
            'hope_and_support' => $values['hope_and_support'],
            'overcoming_obstacles' => $values['overcoming_obstacles'],
        ];
    }
    
    // เปลี่ยนรูปแบบข้อมูลให้เป็น array ที่ต้องการ
    $data4 = $data4Array;
    
    // กำหนดภาควิชาที่ต้องการ
    $departments = [
        'ภาควิชาเคมี',
        'ภาควิชาชีววิทยา',
        'ภาควิชาคณิตศาสตร์',
        'ภาควิชาฟิสิกส์',
        'ภาควิชาวิทยาการคอมพิวเตอร์',
        'ภาควิชาสถิติ',
        'ศูนย์วิเคราะห์ข้อมูลดิจิทัลอัจฉริยะพระจอมเกล้าลาดกระบัง'
    ];

    $rqData = [];
    foreach ($departments as $dept) {
        $rqData[$dept] = [
            'below_normal' => 0,
            'normal' => 0,
            'above_normal' => 0,
            'count' => 0
        ];
    }
    
    $sql3 = "SELECT user.department, result.total_score 
            FROM user 
            INNER JOIN result ON user.id = result.id";
    $stmt = $conn->prepare($sql3);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($results as $result4) {
        $dept = $result4['department'];
        $total_score = $result4['total_score'];
    
        if (isset($rqData[$dept])) {
            // คำนวณ RQ
            if ($total_score < 55) {
                $rqData[$dept]['below_normal']++;
            } elseif ($total_score >= 55 && $total_score <= 69) {
                $rqData[$dept]['normal']++;
            } else {
                $rqData[$dept]['above_normal']++;
            }
    
            // เพิ่มจำนวนผู้ตอบ
            $rqData[$dept]['count']++;
        }
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
    <header class="header">
        <a href="index.html" class="logo"><i class="fa-solid fa-stethoscope"></i> แบบประเมินพลังสุขภาพจิต</a>
    
        <nav class="navbar">
            <div id="close-navbar" class="fas fa-times"></div>
            <a href="homepage.php" class="animated-link">หน้าหลัก</a>
            <a href="#about" class="animated-link">About</a>
            <a href="dashboard.php" class="animated-link">Dashboard</a>
            <a href="check.php" class="animated-link">contact</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
        </div>
    </header>
    <!--  header -->
        <div class="dashboard">
            <div class="message-box">
                <div class="box" >
                    <?php
                    $sql = "SELECT COUNT(*) as id FROM reserch";
                    $query = $conn->prepare($sql);
                    $query->execute();
                    $fetch = $query->fetch();
                    ?>
                    <h1><?= $fetch['id'] ," คน";?></h1>
                    <div class="primary-text1">จำนวนผู้ทำแบบประเมิน</div>
                </div>
                <div class="box" style="background: linear-gradient(45deg, #ff0000, #ff6384)";>
                    <h1>
                        <?php
                            if ($result) {
                                $total_count = $result['below_normal_total_score'] + $result['normal_total_score'] + $result['above_normal_total_score'];
                                echo ($total_count > 0) ? number_format(($result['below_normal_total_score'] / $total_count) * 100) . " %" : "ไม่มีข้อมูลเพื่อแสดงผล";
                            }
                        ?>
                    </h1>
                    <div class="primary-text1">ต่ำกว่าเกณฑ์ปกติ</div>
                </div>
                <div class="box" style="background: linear-gradient(45deg, #ff6f20, #ffff00)";>
                    <h1><?php
                    if ($result) {
                        $total_count = $result['below_normal_total_score'] + $result['normal_total_score'] + $result['above_normal_total_score'];
                        echo ($total_count > 0) ? number_format(($result['normal_total_score'] / $total_count) * 100) . " %" : "ไม่มีข้อมูลเพื่อแสดงผล";
                    }
                    ?></h1>
                    <div class="primary-text1">เกณฑ์ปกติ</div>
                </div>
                <div class="box" style="background: linear-gradient(45deg, #008000, #ffff00)";>
                    <h1><?php
                    if ($result) {
                        $total_count = $result['below_normal_total_score'] + $result['normal_total_score'] + $result['above_normal_total_score'];
                        echo ($total_count > 0) ? number_format(($result['above_normal_total_score'] / $total_count) * 100) . " %" : "ไม่มีข้อมูลเพื่อแสดงผล";
                    }
                    ?></h1>
                    <div class="primary-text1">สูงกว่าเกณฑ์ปกติ</div>
                </div>
            </div>
            <div class="message-box">
                <div class="box">
                    <div class="containered">
                        <div class="primary-text1"></div>
                        <?php
                        $votes = [
                            (int)$result1['below_normal'],
                            (int)$result1['normal'],
                            (int)$result1['above_normal']
                        ];
                        $labels = ['ต่ำกว่าเกณฑ์ปกติ', 'เกณฑ์ปกติ', 'สูงกว่าเกณฑ์ปกติ'];
                        ?>
                        <script>
                            const chartData = {
                                labels: <?php echo json_encode($labels); ?>,
                                data: <?php echo json_encode($votes); ?>
                            };
                        </script>
                        <canvas id="pie_chart" width="80" height="80"></canvas>
                    </div>
                </div>
                <div class="box">
                    <div class="containered">
                        <div class="primary-text1"></div>
                        <?php
                        $votes = ['male' => 0, 'female' => 0, 'LGBTQA+' => 0];
                        foreach ($genders as $gender) {
                            if ($gender['gender'] === 'ชาย') {
                                $votes['male'] = $gender['count'];
                            } elseif ($gender['gender'] === 'หญิง') {
                                $votes['female'] = $gender['count'];
                            } elseif ($gender['gender'] === 'LGBTQA+') {
                                $votes['LGBTQA+'] = $gender['count'];
                            }
                        }
                        $labels1 = ['ชาย', 'หญิง','LGBTQA+'];
                        $data1 = [$votes['male'], $votes['female'], $votes['LGBTQA+']];
                        ?>
                        <script>
                            const chartData1 = {
                                labels1: <?php echo json_encode($labels1); ?>,
                                data1: <?php echo json_encode($data1); ?>
                            };
                        </script>
                        <canvas id="pie_chart2" width="80" height="80"></canvas>
                    </div>
                </div>
                <div class="box">
                    <div class="containered">
                        <div class="primary-text1"></div>
                        <script>
                            const chartData2 = {
                                data4: <?php echo json_encode($data4); ?>
                            };
                        </script>
                        <canvas id="pie_chart3" width="80" height="80"></canvas>
                    </div>
                </div>
            </div>
            <div class="message-box">
                <div class="box">
                    <div class="containered">
                        <section class="table__body">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>ภาควิชา</th>
                                        <th>RQ ระดับน้อย</th>
                                        <th>(%)</th>
                                        <th>RQ ระดับปานกลาง</th>
                                        <th>(%)</th>
                                        <th>RQ ระดับมาก</th>
                                        <th>(%)</th>
                                        <th>RQ ผู้ตอบแบบประเมิน</th>
                                        <th>(%)</th>
                                    </tr>
                                    <tr>
                                        <?php
                                        $index = 1;

                                        foreach ($rqData as $dept => $data) {
                                            $below_normal_count = $data['below_normal'];
                                            $normal_count = $data['normal'];
                                            $above_normal_count = $data['above_normal'];
                                            $total_count = $data['count'];
                                            
                                        
                                            // คำนวณเปอร์เซ็นต์
                                            $below_normal_percentage = $total_count > 0 ? number_format(($below_normal_count / $total_count) * 100, 2) : 0;
                                            $normal_percentage = $total_count > 0 ? number_format(($normal_count / $total_count) * 100, 2) : 0;
                                            $above_normal_percentage = $total_count > 0 ? number_format(($above_normal_count / $total_count) * 100, 2) : 0;
                                        
                                            // แสดงผลในตาราง
                                            echo "<tr>
                                                <td>{$index}</td>
                                                <td>{$dept}</td>
                                                <td>{$below_normal_count}</td>
                                                <td>{$below_normal_percentage}%</td>
                                                <td>{$normal_count}</td>
                                                <td>{$normal_percentage}%</td>
                                                <td>{$above_normal_count}</td>
                                                <td>{$above_normal_percentage}%</td>
                                                <td>{$total_count}</td>
                                                <td>" . number_format(($total_count / $fetch['id']) * 100, 2) . "%" . "</td>
                                            </tr>";

                                            $index++;
                                        }
                                        ?>
                                    </tr>
                                    <tr>
                                        <?php
                                            $index = 1;
                                            // สร้างตัวแปรสำหรับเก็บค่ารวม
                                            $total_below_normal_count = 0;
                                            $total_normal_count = 0;
                                            $total_above_normal_count = 0;
                                            $total_count = 0;

                                            // แสดงผลในตาราง
                                            foreach ($rqData as $dept => $data) {
                                                $below_normal_count = $data['below_normal'];
                                                $normal_count = $data['normal'];
                                                $above_normal_count = $data['above_normal'];
                                                $count = $data['count'];

                                                // เพิ่มค่ารวม
                                                $total_below_normal_count += $below_normal_count;
                                                $total_normal_count += $normal_count;
                                                $total_above_normal_count += $above_normal_count;
                                                $total_count += $count;
                                            }

                                            // แสดงแถวรวม
                                            echo "<th></th>";
                                            echo "<th>Total</th>";
                                            echo "<th>{$total_below_normal_count}</th>";
                                            echo "<th>" . ($total_count > 0 ? number_format(($total_below_normal_count / $total_count) * 100, 2) : 0) . "%</th>";
                                            echo "<th>{$total_normal_count}</th>";
                                            echo "<th>" . ($total_count > 0 ? number_format(($total_normal_count / $total_count) * 100, 2) : 0) . "%</th>";
                                            echo "<th>{$total_above_normal_count}</th>";
                                            echo "<th>" . ($total_count > 0 ? number_format(($total_above_normal_count / $total_count) * 100, 2) : 0) . "%</th>";
                                            echo "<th>{$total_count}</td>";
                                            echo "<th>" . ($total_count > 0 ? number_format(($total_count / $total_count) * 100, 2) : 0) . "%</th>";
                                        ?>
                                </tr>
                            </table>
                        </section>
                    </div>
                </div>
            </div>
            <div class="message-box">
                <div class="box">
                    <div class="containered">
                        <div class="primary-text1"></div>
                        <canvas id="pie_chart4" width="50" height="50"></canvas>
                    </div>
                </div>
                <div class="box">
                    <div class="containered">
                        <div class="primary-text1"></div>
                        <canvas id="pie_chart5" width="50" height="50"></canvas>
                    </div>
                </div>
                <div class="box">
                    <div class="containered">
                        <div class="primary-text1"></div>
                        <script>
                            const chartData6 = {
                                data6: <?php echo json_encode($chronicArray); ?>
                            };
                        </script>
                        <canvas id="pie_chart6" width="50" height="50"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="js/chart1.js"></script>
    <script src="js/chart2.js"></script>
    <script src="js/chart3.js"></script>
    <script src="js/chart4.js"></script>
    <script src="js/chart5.js"></script>
    <script src="js/chart6.js"></script>
    <script src="js/script.js"></script>
</body>
</html>

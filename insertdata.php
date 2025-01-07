<?php
require("dbconnect.php");

$gender = $_POST["gender"];
$year = $_POST["year"];
$department = $_POST["department"];
$chronic_disease = $_POST["chronic_disease"];
$fam_status = $_POST["fam_status"];
$fam_career = $_POST["fam_career"];
$fam_earn = $_POST["fam_earn"];
$gpa = $_POST["gpa"];
$course = $_POST["course"]; 
    
    // แทรกข้อมูลลงในตาราง user
    $sql = "INSERT INTO user(gender, year, department, chronic_disease, gpa, course, fam_status, fam_career, fam_earn) 
            VALUES('$gender', '$year', '$department', '$chronic_disease', '$gpa', '$course', '$fam_status', '$fam_career', '$fam_earn')"; 
    $result = mysqli_query($conn, $sql);

    // ตรวจสอบผลลัพธ์
    if ($result) {           
        header("location:form2.php");
        exit(0);
    }else {
    echo "Error: " . mysqli_error($conn);
}
let circularProgress = document.querySelector(".outer"),
    progressValue = document.querySelector("#score");

let progressStartValue = 0,     // เริ่มที่ 0
    progressEndValue =100,     // เป้าหมายที่ 100
    speed = 20;                 // ความเร็ว

let progress = setInterval(() => {
    progressStartValue++;
    
    // อัปเดตสีแบบ conic-gradient ตามค่า progress
    circularProgress.style.background = `conic-gradient(#7d2ae8 ${progressStartValue * 3.6}deg, #ededed 0deg)`;
    
    // แสดงค่าความคืบหน้า
    progressValue.innerHTML = `${progressStartValue}/40`;

    // หยุดการทำงานเมื่อถึงค่าเป้าหมาย
    if (progressStartValue >= progressEndValue) {
        clearInterval(progress);
    }
}, speed);
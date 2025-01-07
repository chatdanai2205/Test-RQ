const ctx2 = document.getElementById('pie_chart2').getContext('2d');
const pie_chart2 = new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: chartDatagender.labelsGender,
        datasets: [{
            label: 'จำนวน (คน)',
            data: chartDatagender.dataGender,
            backgroundColor: [
                'rgba(255, 99, 132)',
                'rgba(54, 162, 235)',
                'rgba(75, 192, 192)',
            ],
            borderColor: [
                'rgba(255, 255, 255, 1)'
            ],
            borderWidth: 3,
            hoverOffset: 20,
            hoverBorderColor: 'rgba(255, 255, 255, 1)',  // เปลี่ยนสี border เมื่อ hover
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // ปิดการคงสัดส่วนเพื่อปรับขนาด
        plugins: {
            legend: {
                position: 'bottom',
                    labels: {
                     color: '#00000', // เปลี่ยนสีตัวหนังสือที่นี่
                        font: {
                            family: "'Prompt', sans-serif",
                            size: 14 // ขนาดตัวหนังสือ
                    },
                    boxWidth: 20, // ขนาดของ box ใน legend
                    boxHeight: 20, // ความสูงของ box
                }
            },
            title: {
                display: true,
                text: 'กราฟที่ 2 : การกระจายตามเพศ',
                    color: '#00000', // เปลี่ยนสีของชื่อกราฟที่นี่
                        font: {
                    family: "'Prompt', sans-serif",
                    size: 16 // ขนาดตัวหนังสือ
                },
                padding: 20,
                
            },
            tooltip: {
                callbacks: {
                    // ปรับแต่งข้อความที่แสดงใน tooltip
                    label: (context) => {
                        const total = context.chart.data.datasets[0].data.reduce((acc, val) => acc + val, 0);
                        const value = context.raw;  // ค่าของส่วนที่ถูกชี้
                        const percentage = ((value / total) * 100).toFixed(1);  // คำนวณเปอร์เซ็นต์
                        const label = context.label;  // ชื่อหมวดหมู่ เช่น ปี 1, ปี 2, เป็นต้น
                        return `${label}: ${value} คน (${percentage}%)`;  // แสดงจำนวนคนและเปอร์เซ็นต์ใน tooltip
                    }
                }
            },
            datalabels: {
                color: '#00000', // สีของเปอร์เซ็นต์ที่แสดงบนกราฟ
                formatter: (value, context) => {
                    const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                    const percentage = ((value / total) * 100).toFixed(1);
                    return `${percentage}%`; // แสดงเปอร์เซ็นต์
                },
                font: {
                    weight: 'bold',
                    size: 12,
                },
                anchor: 'end', // ข้อความจะถูกวางที่ปลายแท่งกราฟ
            },
        }
    }
});
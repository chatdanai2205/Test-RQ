const ctx4 = document.getElementById('pie_chart4').getContext('2d');
const pie_chart4 = new Chart(ctx4, {
    type: 'pie',
    data: {
        labels: chartDataDepartments.labelsDepartments,
        datasets: [{
            label: 'จำนวน (คน)',
            data: chartDataDepartments.dataDepartments,
            backgroundColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(201, 203, 207, 1)'
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
                    color: '#00000', // เปลี่ยนสีตัวหนังสือใน legend
                    font: {
                        family: "'Prompt', sans-serif",
                        size: 11, // ขนาดตัวหนังสือ
                        borderWidth: 0,
                    },
                    boxWidth: 15, // ขนาดของ box ใน legend
                    boxHeight: 15, // ความสูงของ box
                },
            },
            title: {
                display: true,
                text: 'กราฟที่ 4 : การกระจายตามภาควิชา',
                color: '#00000', // เปลี่ยนสีของชื่อกราฟ
                font: {
                    family: "'Prompt', sans-serif",
                    size: 16 // ขนาดตัวหนังสือ
                }
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
        },
            layout: {
            padding: {
                left: 10,  // ระยะห่างจากขอบซ้าย
                right: 10, // ระยะห่างจากขอบขวา
                top: 10,   // ระยะห่างจากขอบบน
                bottom: 10  // ระยะห่างจากขอบล่าง
            }
        }
    }
});
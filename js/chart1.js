Chart.register(ChartDataLabels);

console.log(chartData);
const ctx = document.getElementById('pie_chart').getContext('2d');
if (!ctx) {
    console.error("Canvas element not found");
}

const pie_chart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: chartData.labels,
        datasets: [{
            label: 'จำนวน (คน)',
            data: chartData.data,
            backgroundColor: [
                'rgba(255, 99, 132, 1)',
                '#FFDF3E',
                'rgba(75, 192, 19, 1)',
            ],
            borderColor: [
                'rgba(255, 255, 255, 1)'
            ],
            borderWidth: 3, // ความหนาของเส้นขอบ
            hoverOffset: 30, // การขยายขนาดเมื่อ hover
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
                    color: '#00000',
                    font: {
                        family: "'Prompt', sans-serif",
                        size: 14,
                    },
                    boxWidth: 20,
                    boxHeight: 20,
                }
            },
            title: {
                display: true,
                text: 'กราฟที่ 1 : การกระจายคะแนนโดยรวม',
                color: '#00000',
                font: {
                    family: "'Prompt', sans-serif",
                    size: 16,
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
            }
        }
    }
});

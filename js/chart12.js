const ctx12 = document.getElementById('pie_chart12').getContext('2d');

const label6 = gpa.map(item => item.gpa);
const pressure6 = gpa.map(item => parseFloat(item.avg_pressure_tolerance)); // ความทนต่อแรงกดดัน
const hope6 = gpa.map(item => parseFloat(item.avg_hope_and_support)); // ความหวังและกำลังใจ
const obstacles6 = gpa.map(item => parseFloat(item.avg_overcoming_obstacles)); // การต่อสู้กับอุปสรรค

const config6 = {
    type: 'bar',
    data: {
        labels: label6, // ป้ายกำกับแกน x
        datasets: [
            {
                label: 'ความทนต่อแรงกดดัน',
                data: pressure6,
                backgroundColor: 'rgba(255, 99, 132, 1)', // สีแท่ง
                borderColor: 'rgba(255, 99, 132, 1)', // สีขอบ
                borderWidth: 1,
                datalabels: {
                    color: function(context) {
                        const value = context.dataset.data[context.dataIndex]; // ค่าของตัวเลขในแต่ละแท่ง
                        if (value < 23) {
                            return 'rgba(255, 99, 132, 1)';
                        } else if (value >= 23 && value <= 34) {
                            return 'rgba(255, 206, 86, 1)';
                        } else {
                            return 'green';
                        }
                    },
                    font: {
                        family: "'Prompt', sans-serif",
                        size: 14,
                    },
                    formatter: function(value) {
                        return value.toFixed(1); // ปรับจำนวนทศนิยมเป็น 1 ตำแหน่ง
                    }
                }
            },
            {
                label: 'ความหวังและกำลังใจ',
                data: hope6,
                backgroundColor: 'rgba(54, 162, 235, 1)', // สีแท่ง
                borderColor: 'rgba(54, 162, 235, 1)', // สีขอบ
                borderWidth: 1,
                datalabels: {
                    color: function(context) {
                        const value = context.dataset.data[context.dataIndex]; // ค่าของตัวเลขในแต่ละแท่ง
                        if (value < 14) {
                            return 'rgba(255, 99, 132, 1)';
                      } else if (value >= 14 && value <= 19) {
                            return 'rgba(255, 206, 86, 1)';
                      } else {
                            return 'green';
                      }
                    },
                    font: {
                        family: "'Prompt', sans-serif",
                        size: 14,
                    },
                    formatter: function(value) {
                        return value.toFixed(1); // ปรับจำนวนทศนิยมเป็น 1 ตำแหน่ง
                    }
                }
            },
            {
                label: 'การต่อสู้กับอุปสรรค',
                data: obstacles6,
                backgroundColor: 'rgba(75, 192, 192, 1)', // สีแท่ง
                borderColor: 'rgba(75, 192, 192, 1)', // สีขอบ
                borderWidth: 1,
                datalabels: {
                    color: function(context) {
                        const value = context.dataset.data[context.dataIndex]; // ค่าของตัวเลขในแต่ละแท่ง
                        if (value < 13) {
                            return 'rgba(255, 99, 132, 1)';
                        } else if (value >= 13 && value <= 18) {
                            return 'rgba(255, 206, 86, 1)';
                        } else {
                            return 'green'; // ถ้าค่าตรงกลาง สีดำ
                        }
                    },
                    font: {
                        family: "'Prompt', sans-serif",
                        size: 14,
                    },
                    formatter: function(value) {
                        return value.toFixed(1); // ปรับจำนวนทศนิยมเป็น 1 ตำแหน่ง
                    }
                }
            }
        ],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'กราฟที่ 12 : คะแนนเฉลี่ยตาม GPA',
                color: '#00000', // เปลี่ยนสีชื่อกราฟเป็นขาว
                font: {
                    family: "'Prompt', sans-serif", // เปลี่ยนฟอนต์ Title
                    size: 16 // ขนาดตัวหนังสือ
                }
            },
            legend: {
                labels: {
                    color: '#00000', // เปลี่ยนสีตัวหนังสือใน legend เป็นขาว
                    font: {
                        family: "'Prompt', sans-serif", // เปลี่ยนฟอนต์ Title
                    },
                    boxWidth: 20, // ขนาดของ box ใน legend
                    boxHeight: 20, // ความสูงของ box
                }
            },
            datalabels: {
                anchor: 'end',
                align: 'top',
                font: {
                    family: "'Prompt', sans-serif",
                    size: 14,
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#00000', // เปลี่ยนสีตัวหนังสือแกน x เป็นขาว
                    font: {
                        family: "'Prompt', sans-serif", // เปลี่ยนฟอนต์ Title
                    }
                },
            },
            y: {
                ticks: {
                    color: '#00000', // เปลี่ยนสีตัวหนังสือแกน y เป็นขาว
                },
                suggestedMax: 40 // หรือ max: 40 เพื่อกำหนดค่าสูงสุดที่ 40
            }
        }
    }
};

// สร้างกราฟ
new Chart(ctx12, config6);

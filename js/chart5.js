const ctx5 = document.getElementById('pie_chart5').getContext('2d');

// จัดเตรียมข้อมูลสำหรับกราฟ
const labels = ['ชาย', 'หญิง', 'LGBTQA+']; // เอาชื่อเพศ
const pressureData = gender.map(item => parseFloat(item.avg_pressure_tolerance)); // ค่าเฉลี่ยความทนต่อแรงกดดัน
const hopeData = gender.map(item => parseFloat(item.avg_hope_and_support)); // ค่าเฉลี่ยความหวังและกำลังใจ
const obstaclesData = gender.map(item => parseFloat(item.avg_overcoming_obstacles)); // ค่าเฉลี่ยการต่อสู้เอาชนะอุปสรรค

// กำหนดค่า configuration ของกราฟ
const cfg = {
  type: 'bar',
  data: {
    labels: labels, // ชื่อเพศ
    datasets: [{
        label: 'ด้านความทนต่อแรงกดดัน',
        data: pressureData, // ดึงข้อมูล pressure_tolerance
        backgroundColor: 'rgba(255, 165, 0, 0.7)', // สีพื้นหลัง
        datalabels: {
          color: function(context) {
            const value = context.dataset.data[context.dataIndex]; // ค่าของตัวเลขในแต่ละแท่ง
            // กำหนดสีตามเงื่อนไขของความทนต่อแรงกดดัน
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
        label: 'ด้านการมีหวังและกำลังใจ',
        data: hopeData, // ดึงข้อมูล hope_and_support
        backgroundColor: 'rgba(54, 162, 235, 0.7)', // สีพื้นหลัง
        datalabels: {
          color: function(context) {
            const value = context.dataset.data[context.dataIndex];
            // กำหนดสีตามเงื่อนไขของความหวังและกำลังใจ
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
        label: 'ด้านการต่อสู้เอาชนะอุปสรรค',
        data: obstaclesData, // ดึงข้อมูล overcoming_obstacles
        backgroundColor: 'rgba(75, 192, 192, 0.7)', // สีพื้นหลัง
        datalabels: {
          color: function(context) {
            const value = context.dataset.data[context.dataIndex];
            // กำหนดสีตามเงื่อนไขของการต่อสู้เอาชนะอุปสรรค
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
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      title: {
        display: true,
        text: 'กราฟที่ 5 : คะแนนเฉลี่ยตามเพศ',
        color: '#00000',
        font: {
          family: "'Prompt', sans-serif",
          size: 16 // ขนาดตัวหนังสือ
        }
      },
      legend: {
        labels: {
          color: '#00000',
          font: {
            family: "'Prompt', sans-serif"
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
          color: '#00000',
          font: {
            family: "'Prompt', sans-serif"
          }
        },
      },
      y: {
        ticks: {
          color: '#00000'
        },
        suggestedMax: 40
      }
    }
  }
};

// สร้างกราฟ
const pie_chart5 = new Chart(ctx5, cfg);

document.querySelectorAll('.likert-btn').forEach(button => {
    button.addEventListener('click', function () {
        const questionName = this.getAttribute('name');
        const value = this.getAttribute('value');
        
        // อัพเดทค่าที่เลือกใน input[type="hidden"] ของคำถามนั้น
        const hiddenInput = document.querySelector(`input[name="${questionName}"]`);
        if (hiddenInput) {
            hiddenInput.value = value;
        }

        // ยกเลิกการเลือกปุ่มอื่นๆ ในแถวเดียวกัน
        const buttonsInRow = this.closest('tr').querySelectorAll('.likert-btn');
        buttonsInRow.forEach(btn => btn.classList.remove('selected'));
        
        // ทำให้ปุ่มนี้เป็นที่เลือก
        this.classList.add('selected');
    });
});

